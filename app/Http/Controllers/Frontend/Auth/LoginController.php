<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating socialUsers for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect socialUsers after login.
     *
     * @var string
     * RouteServiceProvider::HOME
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $socialUser)
    {
        if($socialUser->status == 1){
            return redirect()->route('frontend.dashboard')->with([
                'message' => 'Welcome to blogCms',
                'alert-type' => 'success',
            ]);
        }else{
            return redirect()->route('frontend.index')->with([
                'message' => 'Please Contcat BlogCms Admin',
                'alert-type' => 'warning',
            ]);
        }
    }

    public function redirectToProvidor($providor)
    {
        return Socialite::driver($providor)->redirect();
    }

    public function handleProvidorCallback($providor)
    {
        $socialUser = Socialite::driver($providor)->user();

        dd($socialUser);
        $token = $socialUser->token;

        $id = $socialUser->getId();
        $name = $socialUser->getName();
        $email = $socialUser->getEmail();
        $avatar = $socialUser->getAvatar();

        $user = User::firstOrCreate([
            'email' => $email
        ],[
            'name'              => $name,
            'username'          => trim(Str::lower(Str::replaceArray(' ', ['_'], $name))),
            'email'             => $email,
            'email_verified_at' => Carbon::now(),
            'mobile'            => $id,
            'status'            => 1,
            'receive_email'     => 1,
            'remember_token'    => $token,
            'password'          => Hash::make($email),
        ]);

        if($user->user_image == ''){
            $filename = ''.$user->username . '.jpg';
            $path = public_path('/assets/users/' . $filename);
            Image::make($avatar)->save($path,100);
            $user->update(['user_image'=>$filename]);
        }

        Auth::login($user,true);

        return redirect()->route('frontend.dashboard')->with([
            'message' => 'Login in Successfully',
            'alert-type' => 'success',
        ]);
    }
}
