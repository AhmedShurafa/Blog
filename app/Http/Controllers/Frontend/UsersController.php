<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','web']);
    }

    public function edit_info()
    {
        return view('frontend.users.edit_info');
    }

    public function update_info(Request $request)
    {
         $validation = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email',
            'mobile'        => 'required|numeric',
            'bio'           => 'nullable|min:10',
            'receive_email' => 'required',
            'user_image'    => 'nullable|image|max:20000,mimes:jpeg,jpg,png'
        ]);

        if($validation->fails()){
            return redirect()->back()->withErrors($validation);
        }


        $data['name']           = $request->name;
        $data['email']          = $request->email;
        $data['mobile']         = $request->mobile;
        $data['bio']            = $request->bio;
        $data['receive_email']  = $request->receive_email;

        if($image = $request->file('user_image')){
            if(auth()->user()->user_image != ''){
                if(File::exists('/assets/users/'.auth()->user()->user_image)){
                    unlink('/assets/users/'.auth()->user()->user_image);
                }
            }
            // uploade image user
            $filename = Str::slug(auth()->user()->username).'.'.$image->getClientOriginalExtension();
            $path = public_path('assets/users/'.$filename);

            Image::make($image->getRealPath())->resize(300,300,function($constraint){
                $constraint->aspectRatio();
            })->save($path,100);

            $data['user_image'] = $filename;
        }

        $user = auth()->user()->update($data);
        if($user){
            return redirect()->back()->with([
                'message' => 'Information Updated Successfully',
                'alert-type' => 'success',
            ]);
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    // public function update_password(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'current_password' => 'required',
    //         'password'         => 'required|confirmed',
    //     ]);

    //     if($validator->fails()){
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $user = auth()->user();
    //     if(Hash::check($request->current_password,$user->password)){
    //         $update = $user->update([
    //             'password' => bcrypt($request->password),
    //         ]);

    //         if($update){
    //             return redirect()->back()->with([
    //                 'message' => 'Password Updated Successfully',
    //                 'alert-type' => 'success',
    //             ]);
    //         }else{
    //             return redirect()->back()->with([
    //                 'message' => 'Something was wrong',
    //                 'alert-type' => 'danger',
    //             ]);
    //         }
    //     }
    //     return redirect()->back()->with([
    //         'message' => 'Something was wrong',
    //         'alert-type' => 'danger',
    //     ]);
    // }

    public function index()
    {
        $posts = auth()->user()->posts()->with(['category','user','media'])
            ->withCount('comments')
            ->orderBy('id','desc')->paginate(10);
        // dd($posts);
        return view('frontend.users.dashboard',compact('posts'));
    }


    public function create_post()
    {
        $category = Category::whereStatus(1)->pluck('name','id');
        return view('frontend.users.create_post',compact('category'));
    }

    public function store_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required',
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
                $filename = $post->slug.'-'.time().$i . $file->getClientOriginalExtension();
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

        if($request->status ==1){
            Cache::forget('recent_posts');
        }

        return redirect()->back()->with([
            'message' => 'Post created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit_post($post_id)
    {
        $post = Post::whereSlug($post_id)->orWhere('id',$post_id)
                ->whereUserId(auth()->id())->first();

        if($post){
            $categories  = Category::whereStatus(1)->pluck('name','id');
            return view('frontend.users.edit_post',compact('categories','post'));
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function update_post(Request $request , $post_id)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = Post::whereSlug($post_id)->orWhere('id',$post_id)
                ->whereUserId(auth()->id())->first();

        if($post){

            $data['title']         = $request->title;
            $data['description']   = Purify::clean($request->description);
            $data['status']        = $request->status;
            $data['comment_able']  = $request->comment_able;
            $data['category_id']   = $request->category_id;

            $post->update($data);

            if($request->images && count($request->images) > 0){
                $i=0;
                foreach($request->images as $file){
                    $filename = $post->slug.'-'.time().$i . $file->getClientOriginalExtension();
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

            if($request->status ==1){
                Cache::forget('recent_posts');
            }

            return redirect()->back()->with([
                'message' => 'Post Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('frontend.index')->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function destroy_post($post_id)
    {
        $post = Post::whereSlug($post_id)->orWhere('id',$post_id)
                    ->whereUserId(auth()->id())->first();

        if($post){
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if(File::exists('assets/posts/'.$media->file_name)){
                        unlink('assets/posts/'.$media->file_name);
                    }
                }
            }
            $post->delete();

            return redirect()->back()->with([
                'message' => 'Post Deleted Successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function destroy_post_media($id)
    {
        $media = PostMedia::whereId($id)->first();

        if($media){
            if(File::exists('assets/posts/'.$media->file_name)){
                unlink('assets/posts/'.$media->file_name);
            }
            $media->delete();
            return true;
        }
        return false;
    }

    // Comments
    public function show_comments(Request $request)
    {
        $comments = Comment::query();

        if(isset($request->post) && $request->post != ''){
            $comments = $comments->wherePostId($request->post);

        }else{
            $posts = auth()->user()->posts->pluck('id')->toArray();
            $comments = $comments->whereIn('post_id',$posts);
        }
        $comments = $comments->orderBy('id','desc');
        $comments = $comments->paginate(10);

        return view('frontend.users.comment',compact('comments'));
    }

    public function edit_comment($comment_id)
    {
        $comment  = Comment::whereId($comment_id)->whereHas('post',function($query){
            $query->where('posts.user_id',auth()->id());
        })->first();

        if($comment){
            return view('frontend.users.edit_comment',compact('comment'));
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function update_comment(Request $request, $id)
    {
        dd($id);
    }

    public function destroy_comment($id)
    {
        $comment  = Comment::whereId($id)->whereHas('post',function($query){
            $query->where('posts.user_id',auth()->id());
        })->first();

        if($comment){
            $comment->delete();

            Cache::forget('recent_comments');

            return redirect()->back()->with([
                'message' => 'Post Deleted Successfully',
                'alert-type' => 'success',
            ]);
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }
}
