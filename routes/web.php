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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function (){
    Route::get('post/data', 'PostController@data')->name('post.data');
    Route::resource('post', 'PostController');

    Route::get('notification/data', 'NotificationController@data')->name('notification.data');
    Route::resource('notification', 'NotificationController');

    Route::get('users/export/', 'UserController@export')->name('user.export');
    Route::get('user/data', 'UserController@data')->name('user.data');
    Route::get('user/change-password/{id}', 'UserController@getChangePpassword')->name('user.get.change-password');
    Route::post('user/change-password/{id}', 'UserController@postChangePpassword')->name('user.post.change-password');
    Route::resource('user', 'UserController');

    Route::get('account/data', 'AdminController@data')->name('account.data');
    Route::get('account/change-password/{id}', 'AdminController@getChangePpassword')->name('account.get.change-password');
    Route::post('account/change-password/{id}', 'AdminController@postChangePpassword')->name('account.post.change-password');
    Route::resource('account', 'AdminController');


    Route::get('document/data', 'DocumentController@data')->name('document.data');
    Route::resource('document', 'DocumentController');
    Route::get('exam/data', 'ExamController@data')->name('exam.data');
    Route::resource('exam', 'ExamController');

    Route::get('category/data', 'CategoryController@data')->name('category.data');
    Route::resource('category', 'CategoryController');

});
