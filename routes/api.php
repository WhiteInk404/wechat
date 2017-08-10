<?php

Route::post('auth', 'WechatAppUserAPIController@auth');

// 仅开发时获取 jwt-auth 用
Route::get('token', function () {
    if (!app()->environment('production')) {
        return response(JWTAuth::fromUser(\App\User::find(request('user_id', 3))));
    }
});

/** 小程序下个版本才会支持获取 response header. <2017-05-11 10:26:59> */
Route::group(['middleware' => ['jwt.auth'/*, 'jwt.refresh'*/]], function () {
    Route::resource('activities', 'ActivityAPIController', ['only' => ['index', 'show']]);

    Route::group(['prefix' => 'users'], function () {
        Route::get('me', 'UserAPIController@me');
    });
    // 用户信息
    Route::resource('users', 'UserAPIController');
});