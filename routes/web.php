<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('activity/{activity_id}/team/{team_id}', 'HomeController@activityTeam')->name('activity_team');
});

Route::any('wechat/server', 'ServerController@server');

Auth::routes();
