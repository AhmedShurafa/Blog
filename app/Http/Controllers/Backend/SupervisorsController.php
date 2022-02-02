<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class SupervisorsController extends Controller
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
        if(!auth()->user()->ability('admin','manage_supervisors,show_supervisors')){
            return redirect('admin/index');
        }

        $keyword = (isset(request()->keyword) && request()->keyword !='') ? request()->keyword : null;
        $status = (isset(request()->status) && request()->status !='') ? request()->status : null;
        $sort_by = (isset(request()->sort_by) && request()->sort_by !='') ? request()->sort_by : 'id';
        $order_by = (isset(request()->order_by) && request()->order_by !='') ? request()->order_by : 'desc';
        $limit_by = (isset(request()->limit_by) && request()->limit_by !='') ? request()->limit_by : '10';

        $users = User::whereHas('roles',function($query){
            $query->where('name','editor');
        });

        if($keyword != null){
            $users = $users->search($keyword);
        }
        if($status != null){
            $users = $users->whereStatus($status);
        }

        $users = $users->orderBy($sort_by,$order_by);
        $users = $users->paginate($limit_by);

        return view('backend.supervisors.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supervisors = Permission::pluck('display_name','id');
        return view('backend.supervisors.create',compact('supervisors'));
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
            'name'         => 'required',
            'username'     => 'required|max:20|unique:users',
            'email'        => 'required|email|max:255|unique:users',
            'mobile'       => 'required|numeric|unique:users',
            'status'       => 'required',
            'password'     => 'required|min:8',
            'permissions.*'  => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name']              = $request->name;
        $data['username']          = $request->username;
        $data['email']             = $request->email;
        $data['email_verified_at'] = Carbon::now();
        $data['mobile']            = $request->mobile;
        $data['password']          = bcrypt($request->password);
        $data['status']            = $request->status;
        $data['bio']               = Purify::clean($request->bio);
        $data['receive_email']     = $request->receive_email;

        if($user_image = $request->file('user_image')){

                $filename = Str::slug($request->username) .'.'. $user_image->getClientOriginalExtension();
                $path = public_path('assets/users/'.$filename);

                Image::make($user_image->getRealPath())->resize(300,300,function($constraint){
                    $constraint->aspectRatio();
                })->save($path,100);

            $data['user_image'] = $filename;
        }

        $user = User::create($data);
        $user->attachRole(Role::whereName('editor')->first()->id);
        
        if(isset($request->permissions) && count($request->permissions) > 0){
            $user->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'User created Successfully',
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
        $user = User::find($id);

        if($user){
            return view('backend.supervisors.show',compact('user'));
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if($user){
            $supervisors = Permission::pluck('display_name','id');
            // l nedd all permission this user.
            $userPermission = UserPermission::whereUserId($id)->pluck('permission_id');


            return view('backend.supervisors.edit',compact('user','supervisors','userPermission'));
        }
        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
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
            'name'         => 'required',
            'username'     => 'required|max:20|unique:users,username,'.$id,
            'email'        => 'required|email|max:255|unique:users,email,'.$id,
            'mobile'       => 'required|numeric|unique:users,mobile,'.$id,
            'status'       => 'required',
            'password'     => 'nullable|min:8',
            'user_image'   => 'nullable|mimes:png,jpg,jpeg|max:2000',
            'permissions.*'  => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);

        if($user){

            $data['name']              = $request->name;
            $data['username']          = $request->username;
            $data['email']             = $request->email;
            $data['mobile']            = $request->mobile;
            if(trim($request->password) != ''){
                $data['password']          = bcrypt($request->password);
            }
            $data['status']            = $request->status;
            $data['bio']               = Purify::clean($request->bio);
            $data['receive_email']     = $request->receive_email;


            if($user_image = $request->file('user_image')){

                if($user->user_image !=''){
                    if(File::exists('assets/user/' . $user->user_image)){
                        unlink('assets/user/' . $user->user_image);
                    }
                }

                $filename = Str::slug($request->username) .'.'. $user_image->getClientOriginalExtension();
                $path = public_path('assets/users/'.$filename);

                Image::make($user_image->getRealPath())->resize(300,300,function($constraint){
                    $constraint->aspectRatio();
                })->save($path,100);

                $data['user_image'] = $filename;
            }

            $user->update($data);

            if(isset($request->permissions) && count($request->permissions) > 0){
                $user->permissions()->sync($request->permissions);
            }

            return redirect()->route('admin.supervisors.index')->with([
                'message' => 'User Updated Successfully',
                'alert-type' => 'success',
            ]);

        }else{
            return redirect()->route('admin.supervisors.index')->with([
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
        $user = User::find($id);

        // dd($user);
        if($user){
            if($user->user_image !=''){
                if(File::exists('assets/user/' . $user->user_image)){
                    unlink('assets/user/' . $user->user_image);
                }
            }

            $user->delete();

            return redirect()->route('admin.supervisors.index')->with([
                'message' => 'Supervisors Deleted Successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function removeImage(Request $request)
    {
        $user = User::find($request->user_id);

        if($user){
            if(File::exists('assets/users/'.$user->user_image)){
                unlink('assets/users/'.$user->user_image);
            }
            $user->user_image = null;
            $user->save();
            return 'true';
        }
        return 'false';
    }
}
