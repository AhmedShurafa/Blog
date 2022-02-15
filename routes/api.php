<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\General\GeneralController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Backend for admin panle
Route::get('/chart/comments_chart','Backend\Api\ApiController@comments_chart');
Route::get('/chart/users_chart'   ,'Backend\Api\ApiController@users_chart');
////////////////////////////////////////////


Route::get('/all_posts',                [GeneralController::class ,'get_posts']);
Route::get('/search',                   [GeneralController::class ,'search']);
Route::get('/category/{category_slug}', [GeneralController::class ,'category']);
Route::get('/tag/{slug}',               [GeneralController::class ,'tag']);
Route::get('/archive/{date}',           [GeneralController::class ,'archive']);
Route::get('/author/{author}',          [GeneralController::class ,'author']);


Route::post('login',            [AuthController::class ,'login']);
Route::post('register',         [AuthController::class ,'register']);
Route::post('refresh_token',    [AuthController::class ,'refresh_token']);

Route::group(['middleware' => ['auth:api']],function(){


    Route::get('my_posts',               [UsersController::class,'my_posts']);
    Route::get('create_post',            [UsersController::class,'create']);
    Route::post('store_post',            [UsersController::class,'store_post']);

    Route::get('my_post/{post_id}/edit',    [UsersController::class,'edit_post']);
    Route::patch('update_post/{post_id}',   [UsersController::class,'update_post']);
    Route::delete('destroy_post/{post}',  [UsersController::class,'destroy_post']);

    Route::get('all_comments',             [UsersController::class , 'all_comments']);
    Route::delete('destroy_comment/{id}',  [UsersController::class,'destroy_comment']);

    Route::get('/user_information',         [UsersController::class ,'user_information']);
    Route::put('/update_user_information',[UsersController::class ,'update_user_information']);
    Route::patch('/update_user_password',   [UsersController::class ,'update_user_password']);

    Route::post('logout',                [UsersController::class ,'logout']);
});

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});
