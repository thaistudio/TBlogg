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

Route::get('/', 'PagesController@testFunc');
Route::get('/about', 'PagesController@about');
Route::get('/signIn', 'PagesController@signIn');
Route::get('/signup', 'PagesController@signup');
Route::resource('posts', 'PostController');
Route::get('/social', 'SocialController@index');
Route::get('comment/{post_id}', 'CommentController@create')->name('comment.create');
Route::get('posts/{post_id}/share', 'ShareController@share')->name('post.share');
Route::get('posts/{post_id}/unshare', 'ShareController@unshare')->name('post.unshare');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


