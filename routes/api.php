<?php

Route::post('auth', 'WechatUserAPIController@auth');

// 仅开发时获取 jwt-auth 用
Route::get('token', function () {
    if (!app()->environment('production')) {
        return response(JWTAuth::fromUser(\App\User::find(request('user_id', 3))));
    }
});

/** 小程序下个版本才会支持获取 response header. <2017-05-11 10:26:59> */
Route::group(['middleware' => ['jwt.auth'/*, 'jwt.refresh'*/]], function () {
    Route::group(['middleware' => ['day-sign']], function () {
        Route::post('word/next', 'WordRecordAPIController@next');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('me', 'UserAPIController@me');
    });
    Route::post('reminder', 'ReminderAPIController@store');
    Route::delete('reminder', 'ReminderAPIController@destroy');
});

Route::any('wechat/callback', 'WeChat\IndexController@handle')->name('wechat.callback');
Route::get('wechat/oauth', 'WeChat\Oauth2Controller@handle')->name('wechat.oauth');
