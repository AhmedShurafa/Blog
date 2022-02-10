<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PostTagsController extends Controller
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
        if(!auth()->user()->ability('admin','manage_post_tags,show_post_tags')){
            return redirect('admin/index');
        }

        $keyword = (isset(request()->keyword) && request()->keyword !='') ? request()->keyword : null;
        $sort_by = (isset(request()->sort_by) && request()->sort_by !='') ? request()->sort_by : 'id';
        $order_by = (isset(request()->order_by) && request()->order_by !='') ? request()->order_by : 'desc';
        $limit_by = (isset(request()->limit_by) && request()->limit_by !='') ? request()->limit_by : '10';

        $tags = Tag::withCount('posts');
        if($keyword != null){
            $tags = $tags->search($keyword);
        }

        $tags = $tags->orderBy($sort_by,$order_by);
        $tags = $tags->paginate($limit_by);

        return view('backend.post_tags.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.post_tags.create');
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
            'name'       => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name']          = $request->name;

        Tag::create($data);

        Cache::forget('global_tags');

        return redirect()->route('admin.post_tags.index')->with([
            'message' => 'Tag created Successfully',
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
        $tag = Tag::find($id);

        return view('backend.post_tags.edit',compact('tag'));
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
            'name'       => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tag = Tag::find($id);

        if($tag){

            $data['name']         = $request->name;


            $tag->update($data);

            Cache::forget('global_tags');

            return redirect()->route('admin.post_tags.index')->with([
                'message' => 'Tag Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('admin.post_tags.index')->with([
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
        $tag = Tag::find($id);
        if($tag){

            $tag->delete();
            return redirect()->route('admin.post_tags.index')->with([
                'message' => 'Tag Deleted Successfully',
                'alert-type' => 'success',
            ]);

        }

        return redirect()->route('admin.post_tags.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
}
