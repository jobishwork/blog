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

// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'BlogController@index');
Auth::routes();
Route::get('blog/manage', 'BlogController@manage');
Route::post('blog/search', 'BlogController@search');
Route::get('blog/search', 'BlogController@search');
Route::get('blog/category/{id}', 'BlogController@category');
Route::get('blog/user/{id}', 'BlogController@user');
Route::post('blog/comment/{blog}', 'BlogController@comment');
Route::resource('blog', 'BlogController');
Route::get('post/{id}/destroy', 'BlogController@destroy');

Route::get('topArticles', 'BlogController@topArticles');
Route::get('newArticles', 'BlogController@index');
Route::get('saveArticle/{id}', 'BlogController@saveArticle');

Route::get('password/change','Auth\ResetPasswordController@changeForm');
Route::post('password/change','Auth\ResetPasswordController@change');

Route::get('profile/edit/{id}', 'Auth\RegisterController@edit');
Route::post('profile/update/{id}', 'Auth\RegisterController@update');

Route::get('tags', 'TagController@index');
Route::get('favorite', 'FavoriteController@index');
Route::get('notification', 'NotificationController@index');
Route::get('settings', 'Auth\ResetPasswordController@changeForm');
