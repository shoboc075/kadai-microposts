<?php

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

Route::get('/', 'MicropostsController@index');

Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UserController', ['only' => ['index', 'show']]);
    
    //ログインができていた場合、URLの末尾がusers/{id}のときに実行できる
    Route::group(['prefix' => 'users/{id}'], function () {
    
        //Follow,UnFollowの時に関わる動作
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UserController@followings')->name('users.followings');
        Route::get('followers', 'UserController@followers')->name('users.followers');
        
        
        //Fav,UnFavの時に関わる動作
        Route::post('fav', 'MicropostFavController@store')->name('micropost.fav');
        Route::delete('unfav', 'MicropostFavController@destroy')->name('micropost.unfav');
        Route::get('favoritedMicropost', 'UserController@favorited')->name('users.fav');
        });

    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});

//router 絶対に通らせたい処理。'follow'がなくても動く