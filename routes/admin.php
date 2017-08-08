<?php

Route::get('/', 'HomeController@index');
Route::resource('users', 'UsersController', ['only' => ['index']]);

/* 活动管理 */
Route::group(['prefix' => 'activities', 'as' => 'activity.'], function () {
    Route::post('upload', 'ActivityController@upload')->name('upload');
    Route::post('remove_pic', 'ActivityController@removePic')->name('remove_pic');
});
Route::resource('activities', 'ActivityController');
