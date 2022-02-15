<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\PostResource;
use App\Http\Resources\General\TagsResource;
use App\Http\Resources\Users\CategoriesResource;
use App\Http\Resources\Users\CategoriessResource;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Users\UsersPostsResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // user information
    public function user_information()
    {
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'get information user',
            'data' => new UserResource($user),
        ],200);
    }

    public function update_user_information(Request $request)
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
            return response()->json([
                'errors'  => $validation->errors(),
                'status'  => false,
            ]);
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
            return response()->json([
                'status' => true,
                'message' => 'Information Updated Successfully',
            ]);
        }else{
            return response()->json([
                'status'  => false,
                'message' => 'Something was wrong',
            ]);
        }
    }

    public function update_user_password(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'password'         => 'required|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => true,
                'errors' => $validator->errors(),
            ]);
        }

        $user = auth()->user();

        if(Hash::check($request->current_password,$user->password)){
            $update = $user->update([
                'password' => bcrypt($request->password),
            ]);

            if($update){
                return response()->json([
                    'status' => true,
                    'message' => 'Password Updated Successfully',
                ]);
            }else{
                return response()->json([
                    'status'  => false,
                    'message' => 'Password not Updated',
                ]);
            }
        }
        return response()->json([
            'status'  => false,
            'message' => 'old password not match',
        ]);
    }


    public function my_posts()
    {
        $posts = auth()->user()->posts()
            ->withCount('comments')
            ->orderBy('id','desc')->paginate(10);

        return UsersPostsResource::collection($posts);
    }

    public function create()
    {
        $tags = Tag::all();
        $category = Category::whereStatus(1)->get();

        return [
            'tags'      => TagsResource::collection($tags),
            'category'  => CategoriesResource::collection($category),
        ];
    }

    public function store_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required|int|exists:categories,id',
            'tags.*'      => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => true,
                'errors' => $validator->errors(),
            ]);
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

        if($request->status == 1){
            Cache::forget('recent_posts');
            Cache::forget('global_tags');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Post created Successfully',
        ]);
    }

    public function edit_post($post_id)
    {
        $my_post = Post::whereSlug($post_id)
            ->orWhere('id',$post_id)
            ->whereUserId(auth()->id())
            ->first();

        // dd($my_post);

        $tags = Tag::all();
        $category = Category::whereStatus(1)->get();
        return [
            'post'      => $my_post,
            'tags'      => TagsResource::collection($tags),
            'category'  => CategoriesResource::collection($category),
        ];
    }

    public function update_post(Request $request , $post_id)
    {
        $validator = Validator::make($request->all(),[
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
            'comment_able'=> 'required',
            'category_id' => 'required|int|exists:categories,id',
            'tags.*'      => 'required',

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
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

            return response()->json([
                'status' => 'success',
                'message' => 'Post Updated Successfully',
            ]);

        }else{
            return response()->json([
                'status' => false,
                'message' => 'Something was wrong',
            ]);
        }
    }


    public function destroy_post($post)
    {
        $my_post = Post::whereSlug($post)->orWhere('id',$post)
                    ->whereUserId(auth()->id())->first();

        if($my_post){
            if ($my_post->media->count() > 0) {
                foreach ($my_post->media as $media) {
                    if(File::exists('assets/posts/'.$media->file_name)){
                        unlink('assets/posts/'.$media->file_name);
                    }
                }
            }
            $my_post->delete();

            return response()->json([
                'status' => true,
                'message' => 'Post Deleted Successfully',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something was wrong',
        ]);
    }

    public function delete_post_media($media_id)
    {
        $media = PostMedia::whereId($media_id)->first();

        if($media){
            if(File::exists('assets/posts/'.$media->file_name)){
                unlink('assets/posts/'.$media->file_name);
            }
            $media->delete();

            return response()->json([
                'status' => true,
                'message' => 'Post Deleted Successfully',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Something was wrong',
        ]);
    }
    // End Posts

    // Start Comments
    public function all_comments(Request $request)
    {

        try {
            $comments = Comment::query();

            if(isset($request->post) && $request->post != ''){
                $comments = $comments->wherePostId($request->post);

            }else{
                $posts = auth()->user()->posts->pluck('id')->toArray();
                $comments = $comments->whereIn('post_id',$posts);
            }
            $comments = $comments->orderBy('id','desc');
            $comments = $comments->paginate(10);

            return response()->json([
                'status'  => true,
                'message' => 'All Comments',
                'data'    => $comments,
            ]);
        } catch (\Exception $ex) {

            return response()->json([
                'status'  => false,
                'message' => 'Something was wrong',
            ]);
        }
    }

    public function destroy_comment($id)
    {
        $comment  = Comment::whereId($id)->whereHas('post',function($query){
            $query->where('posts.user_id',auth()->id());
        })->first();

        if($comment){
            $comment->delete();

            Cache::forget('recent_comments');
            return response()->json([
                'status'  => true,
                'message' => 'Comment Deleted Successfully',
            ]);
        }else{
            return response()->json([
                'status'  => false,
                'message' => 'Something was wrong',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Logout Successfully',
        ]);
    }
}
