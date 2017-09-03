<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('activity/{activity_id}/team/{team_id}', 'HomeController@activityTeam')->name('activity_team');
    Route::get('activity/{activity_id}/team/{team_id}/more', 'HomeController@activityTeamMore')->name('activity_team_more');
    Route::get('activity/{activity_id}/team/{team_id}/up', 'HomeController@up')->name('team_up');
});

Route::any('wechat/server', 'ServerController@server');

Route::get('wechat/remind_qr', function () {
    if (!env('APP_DEBUG')) {
        return false;
    }
    /** @var \EasyWeChat\QRCode\QRCode $qr_code */
    $qr_code = EasyWeChat::qrcode();
    $result  = $qr_code->forever('plz_remind_me');

    return $result->ticket;
});

Auth::routes();
