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

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::get('/', 'PagesController@testFunc');
Route::get('/about', 'PagesController@about')->middleware('verified');
Route::get('/signIn', 'PagesController@signIn');
Route::get('/signup', 'PagesController@signup');

Route::resource('posts', 'PostController');

Route::get('/social', 'SocialController@index');
Route::get('comment/{post_id}', 'CommentController@create')->name('comment.create');
Route::get('posts/{post_id}/share', 'ShareController@share')->name('post.share');
Route::get('posts/{post_id}/unshare', 'ShareController@unshare')->name('post.unshare');
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('h', function () {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    print_r($redis);
});

Route::get('/{user_id}/collection', 'PostController@show_collection')->name('post.show_collection');
Route::get('/{post_id}/collect', 'PostController@collect')->name('post.post.collect');
Route::get('/{post_id}/discollect', 'PostController@dis_collect')->name('post.dis_collect');



