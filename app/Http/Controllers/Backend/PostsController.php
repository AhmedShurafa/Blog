<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        if(auth()->check()){
            $this->middleware('auth');
        }else{
            return redirect('admin.index');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->ability('admin','show_posts')){
            return redirect('admin/index');
        }

        $keyword = (isset(request()->keyword) && request()->keyword !='') ? request()->keyword : null;
        $category = (isset(request()->category_id) && request()->category_id !='') ? request()->category_id : null;
        $status = (isset(request()->status) && request()->status !='') ? request()->status : null;
        $sort_by = (isset(request()->sort_by) && request()->sort_by !='') ? request()->sort_by : 'id';
        $order_by = (isset(request()->order_by) && request()->order_by !='') ? request()->order_by : 'desc';
        $limit_by = (isset(request()->limit_by) && request()->limit_by !='') ? request()->limit_by : '10';

        $posts = Post::with(['user','category','comments'])->wherePostType('post');

        if($keyword != null){
            $posts = $posts->search($keyword);
        }
        if($category != null){
            $posts = $posts->whereCategoryId($category);
        }
        if($status != null){
            $posts = $posts->whereStatus($status);
        }

        $posts = $posts->orderBy($sort_by,$order_by);
        $posts = $posts->paginate($limit_by);

        $categories = Category::orderBy('id','desc')->get();
        return view('backend.posts.index',compact('posts','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::pluck('name','id');
        $categories = Category::orderBy('id','desc')->pluck('name','id');
        return view('backend.posts.create',compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required',
            'images.*'    => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['title']         = $request->title;
        $data['description']   = Purify::clean($request->description);
        $data['status']        = $request->status;
        $data['comment_able']  = $request->comment_able;
        $data['category_id']   = $request->category_id;

        $post = auth()->user()->posts()->create($data);

        if($request->images && count($request->images) > 0){
            $i=0;
            foreach($request->images as $file){
                $filename = $post->slug.'-'.time().$i .'.'. $file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = public_path('assets/posts/'.$filename);
                Image::make($file->getRealPath())->resize(800,null,function($constraint){
                    $constraint->aspectRatio();
                })->save($path,100);

                $post->media()->create([
                    'file_name' => $filename,
                    'file_type' => $file_type,
                    'file_size' => $file_size,
                ]);
            }
        }

        if(count($request->tags) > 0){
            $new_tags = [];
            foreach($request->tags as $tag){
                $tag = Tag::firstOrCreate([
                    'id' => $tag
                ],[
                    'name' => $tag
                ]);
                $new_tags[]= $tag->id;
            }
            $post->tags()->sync($new_tags);
        }


        if($request->status ==1){
            Cache::forget('recent_posts');
            Cache::forget('global_tags');
        }
        return redirect()->route('admin.posts.index')->with([
            'message' => 'Post created Successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tags = Tag::pluck('name','id');
        $post = Post::with(['media','category','user','comments'])
                ->wherePostType('post')->find($id);

        return view('backend.posts.show',compact('post','tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tags = Tag::pluck('name','id');

        $categories = Category::orderBy('id','desc')->pluck('name','id');
        $post = Post::with(['media','category'])->wherePostType('post')->find($id);

        return view('backend.posts.edit',compact('categories','post','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required',
            'images.*'    => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = Post::wherePostType('post')->find($id);

        if($post){

            $data['title']         = $request->title;
            $data['slug']          = null;
            $data['description']   = Purify::clean($request->description);
            $data['status']        = $request->status;
            $data['comment_able']  = $request->comment_able;
            $data['category_id']   = $request->category_id;

            $post->update($data);

            if($request->images && count($request->images) > 0){
                $i=0;
                foreach($request->images as $file){
                    $filename = $post->slug.'-'.uniqid().$i .'.'. $file->getClientOriginalExtension();
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $path = public_path('assets/posts/'.$filename);
                    Image::make($file->getRealPath())->resize(800,null,function($constraint){
                        $constraint->aspectRatio();
                    })->save($path,100);

                    $post->media()->create([
                        'file_name' => $filename,
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                    ]);
                }
            }

            if(count($request->tags) > 0){
                $new_tags = [];
                foreach($request->tags as $tag){
                    $tag = Tag::firstOrCreate([
                        'id' => $tag
                    ],[
                        'name' => $tag
                    ]);
                    $new_tags[]= $tag->id;
                }
                $post->tags()->sync($new_tags);
            }

            if($request->status ==1){
                Cache::forget('recent_posts');
                Cache::forget('global_tags');

            }

            return redirect()->route('admin.posts.index')->with([
                'message' => 'Post Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('admin.posts.index')->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::wherePostType('post')->find($id);
        if($post){
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if(File::exists('assets/posts/'.$media->file_name)){
                        unlink('assets/posts/'.$media->file_name);
                    }
                }
            }
            $post->delete();

            return redirect()->route('admin.posts.index')->with([
                'message' => 'Post Deleted Successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->route('admin.posts.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function removeImage(Request $request)
    {
        $media = PostMedia::whereId($request->media_id)->first();

        if($media){
            if(File::exists('assets/posts/'.$media->file_name)){
                unlink('assets/posts/'.$media->file_name);
            }
            $media->delete();
            return true;
        }
        return false;
    }
}
