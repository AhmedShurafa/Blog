<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class PostCommentsController extends Controller
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
        if(!auth()->user()->ability('admin','show_post_comments,manage_post_comments')){
            return redirect('admin/index');
        }

        $keyword = (isset(request()->keyword) && request()->keyword !='') ? request()->keyword : null;
        $postId = (isset(request()->post_id) && request()->post_id !='') ? request()->post_id : null;
        $status = (isset(request()->status) && request()->status !='') ? request()->status : null;
        $sort_by = (isset(request()->sort_by) && request()->sort_by !='') ? request()->sort_by : 'id';
        $order_by = (isset(request()->order_by) && request()->order_by !='') ? request()->order_by : 'desc';
        $limit_by = (isset(request()->limit_by) && request()->limit_by !='') ? request()->limit_by : '10';

        $comments = Comment::query();

        if($keyword != null){
            $comments = $comments->search($keyword);
        }
        if($postId != null){
            $comments = $comments->where('post_id',$postId);
        }
        if($status != null){
            $comments = $comments->whereStatus($status);
        }

        $comments = $comments->orderBy($sort_by,$order_by);
        $comments = $comments->paginate($limit_by);

        $posts = Post::wherePostType('post')->get();
        return view('backend.post_comments.index',compact('posts','comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);

        return view('backend.post_comments.edit',compact('comment'));
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
            'name'        => 'required',
            'email'       => 'required|email',
            'url'         => 'required|url',
            'status'      => 'required',
            'comment'     => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::find($id);

        if($comment){

            $data['name']     = $request->name;
            $data['email']    = $request->email;
            $data['url']      = $request->url;
            $data['status']   = $request->status;
            $data['comment']  = Purify::clean($request->comment);

            $comment->update($data);

            Cache::forget('recent_comments');

            return redirect()->route('admin.post_comments.index')->with([
                'message' => 'Comment Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('admin.post_comments.index')->with([
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
        $comment = Comment::find($id);

        $comment->delete();

        return redirect()->route('admin.post_comments.index')->with([
            'message' => 'Comment Deleted Successfully',
            'alert-type' => 'success',
        ]);
    }

}
