<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewCommentForPostOwnerNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class IndexController extends Controller
{
    public function index()
    {
        $posts = Post::with(['media','user'])
            ->whereHas('category', function ($query){
                $query->whereStatus(1);
            })->whereHas('user', function ($query){
                $query->whereStatus(1);
            })->post()->active()->orderBy('id','desc')
            ->paginate(5);

        return view('frontend.index',compact('posts'));
    }

    public function search(Request $request)
    {
        $keyword =isset($request->keyword) && $request->keyword != '' ? $request->keyword : null;

        $posts = Post::with(['user','media'])
            ->whereHas('category',function ($query){
                $query->whereStatus(1);
            })->whereHas('user',function ($query){
                $query->whereStatus(1);
            });

        if($keyword != null){
            $posts = $posts->search($keyword,null,true);
        }

        $posts = $posts->post()->active()
        ->orderBy('id','desc')->paginate(10);

        return view('frontend.index',compact('posts'));
    }

    public function category($slug)
    {
        $category = Category::whereStatus(1)->whereSlug($slug)->first()->id;

        if($category){

            $posts = Post::with(['user','media'])
                ->whereCategoryId($category)
                ->active(1)
                ->post('post')
                ->orderBy('id','desc')
                ->paginate(5);

            return view('frontend.index',compact('posts'));
        }
        return redirect()->route('frontend.index');
    }

    public function archive($date)
    {
        $exploded = explode("-",$date);
        $month = $exploded[0];
        $year = $exploded[1];

        $posts = Post::with(['user','media'])
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->active()
            ->post()
            ->orderBy('id','desc')
            ->paginate(5);

        return view('frontend.index',compact('posts'));
    }

    public function author($username)
    {
        $user = User::whereUsername($username)->whereStatus(1)->first()->id;

        if($user){
            $posts = Post::with(['user','media'])
            ->whereUserId($user)
            ->active()
            ->post()
            ->orderBy('id','desc')
            ->paginate(5);

        return view('frontend.index',compact('posts'));
        }
        return redirect()->route('frontend.index');
    }


    public function show_post($slug)
    {
        $post = Post::with(['category','user','comments','media',
            'approved_comments'=>function($query){
                $query->orderBy('id','desc');
            }
        ]);

        $post = $post->whereHas('category',function ($query){
                $query->whereStatus(1);
            })->whereHas('user',function ($query){
                $query->whereStatus(1);
            })->wherePostType('post')->whereStatus(1);

        $post = $post->whereSlug($slug)->first();

        if($post){
            return view('frontend.post',compact('post'));
        }else{
            return redirect()->route('frontend.index');
        }
    }

    public function show_page($slug)
    {
        $page = Post::with(['user','media']);

        $page = $page->whereSlug($slug)->wherePostType('page')->whereStatus(1)->first();

        if($page){
            return view('frontend.page',compact('page'));
        }else{
            return redirect()->route('frontend.index');
        }
    }

    public function add_comment(Request $request , $slug)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:2',
            'email' => 'required|email',
            'url' => 'nullable|url',
            'comment' => 'required|min:5',
        ]);

        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $post = Post::whereSlug($slug)->whereStatus(1)->wherePostType('post')->first();
        if($post){

            $user_id = auth()->check() ? auth()->id : null;

            $data['name']       = $request->name;
            $data['email']      = $request->email;
            $data['url']        = $request->url;
            $data['ip_address'] = $request->ip();
            $data['comment']    = Purify::clean($request->comment);
            $data['post_id']    = $post->id;
            $data['user_id']    = $user_id;

            $comment = $post->comments()->create($data);
            // Comment::create($data);


            // when add comment send notify for owner this post
            if($comment){
                //if add comment in my post , dont send my notify when add comment
                if(auth()->guest() || auth()->id != $post->user_id){
                    // l want to get owner this post
                    $post->user->notify(new NewCommentForPostOwnerNotify($comment));
                }
            }


            return redirect()->back()->with([
                'message' => 'Comment Add Successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Something was weong',
            'alert-type' => 'danger',
        ]);
    }

    public function show_contact()
    {
        return view('frontend.contact');
    }

    public function addContact(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name'    => 'required',
            'email'   => 'required|email',
            'moblie'  => 'required|numeric',
            'title'   => 'required|min:5',
            'message' => 'required|min:10',
        ]);

        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }

        Contact::create($request->all());

        return redirect()->back()->with([
            'message' => 'Message Add Successfully',
            'alert-type' => 'success',
        ]);
    }


}
