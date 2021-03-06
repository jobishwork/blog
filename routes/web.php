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
Route::get('/home', 'BlogController@index');
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
Route::post('upload-image', 'BlogController@uploadImage');


Route::get('saved_articles', 'BlogController@savedArticles');
Route::get('topArticles', 'BlogController@topArticles');
Route::get('newArticles', 'BlogController@newArticles');
Route::get('saveArticle/{id}', 'BlogController@saveArticle');

Route::get('password/change','Auth\ResetPasswordController@changeForm');
Route::post('password/change','Auth\ResetPasswordController@change');


Route::get('tags', 'TagController@index');
Route::get('favorite', 'FavoriteController@index');
Route::get('notification', 'NotificationController@index');
Route::get('settings', 'Auth\ResetPasswordController@changeForm');

Route::get('following/{id}', 'FollowerController@store');
Route::get('followers/{id}', 'FollowerController@followers');
Route::get('followings/{id}', 'FollowerController@followings');


Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('favorite-category', 'FavoriteCategoryController@index');
Route::post('favorite-category', 'FavoriteCategoryController@store');

Route::get('privacy-policy', function () {
     return view('privacy_policy');
 });

Route::get('terms', function () {
     return view('terms');
 });

Route::get('/users/list', 'UserController@index');

Route::get('profile/edit/{id}', 'Auth\RegisterController@edit');
Route::post('profile/update/{id}', 'Auth\RegisterController@update');
Route::get('users/edit/{id}', 'Auth\RegisterController@edit');
Route::get('register/verify/{confirmationCode}','Auth\RegisterController@confirm');
Route::get('verify/resend/{id}', 'Auth\RegisterController@resend');

Route::get('/transactions/list', 'TransactionController@index');
Route::get('points/create', 'TransactionController@create');
Route::post('/transactions/add', 'TransactionController@store');
Route::get('unlock/article/{id}', 'TransactionController@unlockArticle');
Route::post('unlock/article/{id}', 'TransactionController@unlockArticleAction');

Route::get('message/create/{id}', 'MessageController@create');
Route::post('message/store/{id}', 'MessageController@store');
Route::get('inbox', 'MessageController@inbox');
Route::get('inbox_show/{id}', 'MessageController@inboxShow');
Route::get('sent_messages', 'MessageController@sentMessages');
Route::get('sent_message_show/{id}', 'MessageController@sentMessageShow');

Route::get('like/{id}','LikeController@store');
Route::get('dislike/{id}','DislikeController@store');

Route::get('report-article/{id}', 'ReportController@store');
Route::get('reported-articles/list', 'ReportController@index');

Route::get('suspend-article/{id}', 'SuspendController@store');

Route::get('my-profile', 'ProfileController@index');

Route::get('/email', 'EmailController@send');

Route::get('/rating/{id}', 'RatingController@store');


