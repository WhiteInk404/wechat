<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('teams/{team_id}', function () {
        return 1;
    });
});

Auth::routes();
