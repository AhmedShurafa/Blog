<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255' , 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'numeric', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_image' => ['nullable', 'image', 'max:20000','mimes:jpeg,png,jpg'],
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validation->errors(),
            ],201);
        }

        $user =  User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
            'status'    => 1,
            'password'  => Hash::make($request->password),
        ]);

        $user->attachRole(Role::whereName('user')->first()->id);

        $token = $this->getRefreshToken($request->email,$request->password);

        return response()->json([
            'status' => true,
            'data' => $user,
            'token' => $token,
        ]);
    }


    public function login(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){

            $email = Auth::user()->email;

            return $this->getRefreshToken($email,$request->password);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'These credentials do not match our records.',
            ],500);
        }
    }

    public function getRefreshToken($email,$password)
    {
        $verifyValue = app()->environment() == 'local' ? false : true;

        $response = Http::withOptions([
            'verify' => $verifyValue,
        ])->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ]);

        return response()->json($response->json(), 200);
    }

    public function refresh_token(Request $request)
    {
        try {
            $refresh_token = $request->header('RefreshTokenCode');

            $verifyValue = app()->environment() == 'local' ? false : true;


            $response = Http::withOptions([
                'verify' => $verifyValue,
                ])->post(config('app.url') . '/oauth/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => config('passport.personal_access_client.id'),
                    'client_secret' => config('passport.personal_access_client.secret'),
                    'scope' => '*',
            ]);

            return response()->json($response->json(),200);

        } catch (\Exception $ex) {

            return $response->json('unauthorized ',200);

        }

    }


}
