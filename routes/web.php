<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth as FrontEnd;
use App\Http\Controllers\Backend\Auth as Backend;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/login', ['as' => 'show_login_form' , 'uses' => 'Frontend\Auth\LoginController@showLoginForm']);

Route::get('/', ['as'=>'frontend.index' , 'uses' => 'Frontend\IndexController@index']);

// Authentication user Route
Route::get('/login',                            ['as' => 'frontend.show_login_form',        'uses' => 'Frontend\Auth\LoginController@showLoginForm']);
Route::post('login',                            ['as' => 'frontend.login',                  'uses' => 'Frontend\Auth\LoginController@login']);
Route::post('logout',                           ['as' => 'frontend.logout',                 'uses' => 'Frontend\Auth\LoginController@logout']);
Route::get('register',                          ['as' => 'frontend.show_register_form',     'uses' => 'Frontend\Auth\RegisterController@showRegistrationForm']);
Route::post('register',                         ['as' => 'frontend.register',               'uses' => 'Frontend\Auth\RegisterController@register']);
Route::get('password/reset',                    ['as' => 'password.request',                'uses' => 'Frontend\Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email',                   ['as' => 'password.email',                  'uses' => 'Frontend\Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}',            ['as' => 'password.reset',                  'uses' => 'Frontend\Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset',                   ['as' => 'password.update',                 'uses' => 'Frontend\Auth\ResetPasswordController@reset']);
Route::get('email/verify',                      ['as' => 'verification.notice',             'uses' => 'Frontend\Auth\VerificationController@show']);
Route::get('/email/verify/{id}/{hash}',         ['as' => 'verification.verify',             'uses' => 'Frontend\Auth\VerificationController@verify']);
Route::post('email/resend',                     ['as' => 'verification.resend',             'uses' => 'Frontend\Auth\VerificationController@resend']);

Route::group(['middleware' => 'verified'],function(){
    Route::get('/dashboard' ,                   ['as' => 'frontend.dashboard',              'uses'=>'Frontend\UsersController@index']);
    // this is route for user
    Route::get('/edit-info' ,                   ['as' => 'users.edit_info',               'uses'=>'Frontend\UsersController@edit_info']);
    Route::post('/edit-info' ,                  ['as' => 'users.update_info',             'uses'=>'Frontend\UsersController@update_info']);
    Route::post('/update-password' ,            ['as' => 'users.update_password',         'uses'=>'Frontend\UsersController@update_password']);

    // this is route for post
    Route::get('/create-post' ,                 ['as' => 'users.post.create',               'uses'=>'Frontend\UsersController@create_post']);
    Route::post('/create-post' ,                ['as' => 'users.post.store',                'uses'=>'Frontend\UsersController@store_post']);

    Route::get('/edit-post/{post_id}' ,             ['as' => 'users.post.edit',             'uses'=>'Frontend\UsersController@edit_post']);
    Route::put('/update-post/{post_id}' ,           ['as' => 'users.post.update',           'uses'=>'Frontend\UsersController@update_post']);
    Route::delete('/destroy-post/{post_id}' ,       ['as' => 'users.post.destroy',           'uses'=>'Frontend\UsersController@destroy_post']);
    Route::post('/delete-post-media/{media_id}',    ['as' => 'users.post.media.destroy',    'uses'=>'Frontend\UsersController@destroy_post_media']);

    // this is route for comment
    Route::get('/comments' ,                        ['as' => 'users.comments',                  'uses'=>'Frontend\UsersController@show_comments']);

    Route::get('/edit-comment/{comment_id}' ,             ['as' => 'users.comment.edit',             'uses'=>'Frontend\UsersController@edit_comment']);
    Route::put('/update-comment/{comment_id}' ,           ['as' => 'users.comment.update',           'uses'=>'Frontend\UsersController@update_comment']);
    Route::delete('/destroy-comment/{comment_id}' ,       ['as' => 'users.comment.destroy',           'uses'=>'Frontend\UsersController@destroy_comment']);


    //notification
    Route::any('user/notifications/get',                 ['as' => 'users.notification.get',  'uses'=>'Frontend\NotificationsController@getNotifications']);
    Route::any('user/notifications/read',                ['as' => 'users.notification.read',  'uses'=>'Frontend\NotificationsController@markAsRead']);
    Route::any('user/notifications/read/{id}',           ['as' => 'users.notification.get',  'uses'=>'Frontend\NotificationsController@markAsReadAndRedirect']);
});




// Authentication Admin Route
Route::group(['prefix'=>'admin' , 'as'=>'admin.'] , function (){
    Route::get('/login', [Backend\LoginController::class, 'showLoginForm'])->name('show_login_form');
    Route::post('login', [Backend\LoginController::class, 'login'])->name('login');
    Route::post('logout', [Backend\LoginController::class, 'logout'])->name('logout');
    Route::get('password/reset', [Backend\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [Backend\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [Backend\ResetPasswordController::class,'showResetForm'])->name('password.reset');
    Route::post('password/reset', [Backend\ResetPasswordController::class,'reset'])->name('password.update');
    Route::get('email/verify', [Backend\VerificationController::class,'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [Backend\VerificationController::class,'verify'])->name('verification.verify');
    Route::post('email/resend', [Backend\VerificationController::class,'veriresendfy'])->name('verification.resend');
});
// Contact us
Route::get('/contact-us' ,      ['as' => 'frontend.contact' , 'uses'=>'Frontend\IndexController@show_contact']);
Route::post('/contact-us',      ['as' => 'addContact' , 'uses'=>'Frontend\IndexController@addContact']);

// serach
Route::get('/search',                      ['as' => 'frontend.search' ,         'uses' => 'Frontend\IndexController@search']);
Route::get('/category/{category_slug}',    ['as' => 'frontend.category.posts' , 'uses' => 'Frontend\IndexController@category']);
Route::get('/archive/{date}',              ['as' => 'frontend.archive.posts' ,  'uses' => 'Frontend\IndexController@archive']);
Route::get('/author/{username}',           ['as' => 'frontend.author.posts' ,   'uses' => 'Frontend\IndexController@author']);



// show page
Route::get('/{page}',               ['as' => 'page.show' , 'uses'=>'Frontend\IndexController@show_page']);

// show post
Route::get('post/{post}',           ['as' => 'post.show' ,        'uses'=>'Frontend\IndexController@show_post']);
Route::post('/post/comment/{slug}', ['as' => 'post.add_comment' , 'uses'=>'Frontend\IndexController@add_comment']);

