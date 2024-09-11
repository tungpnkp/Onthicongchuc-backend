<?php

use Illuminate\Http\Request;

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
Route::group(['namespace' => 'Api'], function () {
    Route::post("login", "User\UserController@login")->name("login");
    Route::post("register", "User\UserController@register")->name("register");
    Route::post("forgot-password", "User\UserController@ForgotPassword")->name("forgot-password");
    Route::post("reset-password", "User\UserController@ResetPassword")->name("reset-password");
//    Route::group(['middleware' => 'auth:api', 'as' => "user."], function () {
        Route::get("profile", "User\UserController@profile")->name("get.profile");
        Route::post("profile", "User\UserController@UpdateProfile")->name("update.profile");
        Route::post("change-password", "User\UserController@ChangePassword")->name("change-password");
        Route::post("logout", "User\UserController@Logout")->name("logout");

//        Notification
        Route::get('notifications', 'NotificationController@GetList')->name("notifications.get-list");
        Route::get('notifications/{id}', 'NotificationController@GetDetail')->name("notifications.get-detail");
        Route::post('notifications/status/{id}', 'NotificationController@ChangeStatus')->name("notifications.change-status");
//        Bài viết
        Route::get('post/{id}', 'PostController@detail')->name("post.detail");
        //    Lý thuyết
        Route::get('documents', 'DocumentController@GetList')->name("documents.get-list");
        Route::post('documents/add', 'DocumentController@add')->name("documents.add");
        Route::get('documents/category/{categoryId}', 'DocumentController@getListByCategory')->name("documents.list_by_category");
        Route::get('documents/{id}', 'DocumentController@GetDetail')->name("documents.get-detail");
        //    Bài test
        Route::get('exams', 'ExamController@GetList')->name("exams.get-list");
        Route::post('exams/add', 'ExamController@add')->name("exams.add");
        Route::get('exams/category/{categoryId}', 'ExamController@getListByCategory')->name("exams.list_by_category");
        Route::get('exams/{id}', 'ExamController@GetDetail')->name("exams.get-detail");
//        Thống kê
        Route::get('statistics', 'StatisticController@GetList')->name("statistics.get-list");
        Route::get('statistics/{exam_id}', 'StatisticController@GetDetail')->name("statistics.get-detail");
//      Kết quả bài test
        Route::post('result/{id}', 'ExamController@result')->name("exams.result");
        // category
        Route::get('category', 'CategoryController@index')->name("category.index");
//    });
});

