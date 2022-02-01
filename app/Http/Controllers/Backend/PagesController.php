<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use Intervention\Image\Facades\Image;

class PagesController extends Controller
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
        if(!auth()->user()->ability('admin','show_pages')){
            return redirect('admin/index');
        }

        $keyword = (isset(request()->keyword) && request()->keyword !='') ? request()->keyword : null;
        $category = (isset(request()->category_id) && request()->category_id !='') ? request()->category_id : null;
        $status = (isset(request()->status) && request()->status !='') ? request()->status : null;
        $sort_by = (isset(request()->sort_by) && request()->sort_by !='') ? request()->sort_by : 'id';
        $order_by = (isset(request()->order_by) && request()->order_by !='') ? request()->order_by : 'desc';
        $limit_by = (isset(request()->limit_by) && request()->limit_by !='') ? request()->limit_by : '10';

        $pages = Page::wherePostType('page');

        if($keyword != null){
            $pages = $pages->search($keyword);
        }
        if($category != null){
            $pages = $pages->whereCategoryId($category);
        }
        if($status != null){
            $pages = $pages->whereStatus($status);
        }

        $pages = $pages->orderBy($sort_by,$order_by);
        $pages = $pages->paginate($limit_by);

        $categories = Category::orderBy('id','desc')->get();
        return view('backend.pages.index',compact('pages','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('id','desc')->pluck('name','id');
        return view('backend.pages.create',compact('categories'));
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
            'category_id' => 'required',
            'images.*'    => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['title']         = $request->title;
        $data['description']   = Purify::clean($request->description);
        $data['status']        = $request->status;
        $data['post_type']     = 'page';
        $data['comment_able']  = '0';
        $data['category_id']   = $request->category_id;

        $page = auth()->user()->posts()->create($data);

        if($request->images && count($request->images) > 0){
            $i=0;
            foreach($request->images as $file){
                $filename = $page->slug.'-'.time().$i . $file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = public_path('assets/pages/'.$filename);

                Image::make($file->getRealPath())->resize(800,null,function($constraint){
                    $constraint->aspectRatio();
                })->save($path,100);

                $page->media()->create([
                    'file_name' => $filename,
                    'file_type' => $file_type,
                    'file_size' => $file_size,
                ]);
            }

        }

        return redirect()->route('admin.pages.index')->with([
            'message' => 'Pages created Successfully',
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
        $page = Page::with(['media'])->wherePostType('page')->find($id);
        return view('backend.pages.show',compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::orderBy('id','desc')->pluck('name','id');
        $page = Page::with(['media'])->wherePostType('page')->find($id);

        return view('backend.pages.edit',compact('categories','page'));
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
            'category_id' => 'required',
            'images.*'    => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page = Page::wherePostType('page')->find($id);

        if($page){

            $data['title']         = $request->title;
            $data['slug']          = null;
            $data['description']   = Purify::clean($request->description);
            $data['status']        = $request->status;
            $data['category_id']   = $request->category_id;

            $page->update($data);

            if($request->images && count($request->images) > 0){
                $i=0;
                foreach($request->images as $file){
                    $filename = $page->slug.'-'.uniqid().$i .'.'. $file->getClientOriginalExtension();
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $path = public_path('assets/pages/'.$filename);
                    Image::make($file->getRealPath())->resize(800,null,function($constraint){
                        $constraint->aspectRatio();
                    })->save($path,100);

                    $page->media()->create([
                        'file_name' => $filename,
                        'file_type' => $file_type,
                        'file_size' => $file_size,
                    ]);
                }
            }

            return redirect()->route('admin.pages.index')->with([
                'message' => 'Page Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('admin.pages.index')->with([
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
        $page = Page::wherePostType('page')->find($id);

        if($page){
            if ($page->media->count() > 0) {
                foreach ($page->media as $media) {
                    if(File::exists('assets/pages/'.$media->file_name)){
                        unlink('assets/pages/'.$media->file_name);
                    }
                }
            }
            $page->delete();

            return redirect()->route('admin.pages.index')->with([
                'message' => 'Page Deleted Successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->route('admin.pages.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function removeImage(Request $request)
    {
        $media = PostMedia::whereId($request->media_id)->first();

        if($media){
            if(File::exists('assets/pages/'.$media->file_name)){
                unlink('assets/pages/'.$media->file_name);
            }
            $media->delete();
            return true;
        }
        return false;
    }
}
