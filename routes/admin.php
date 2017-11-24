<?php

Route::get('/', 'HomeController@index');
Route::resource('users', 'UsersController', ['only' => ['index']]);

/* 活动管理 */
Route::group(['prefix' => 'activities', 'as' => 'activity.'], function () {
    Route::post('upload', 'ActivityController@upload')->name('upload');
    Route::post('remove_pic', 'ActivityController@removePic')->name('remove_pic');
});
Route::resource('activities', 'ActivityController');

/* 单词本管理 */
Route::group(['prefix' => 'wordbook', 'as' => 'wordbook.'], function () {
    Route::post('upload', 'WordbookController@upload')->name('upload');
    Route::get('{id}/contents', 'WordbookController@contents')->name('contents');
    Route::put('{id}/sort', 'WordbookController@sort')->name('sort');
});
Route::resource('wordbook', 'WordbookController');

Route::group(['as' => 'wechat.', 'namespace' => '\Wechat', 'prefix' => 'wechat'], function () {
    Route::get('wx/config', 'AccountController@index')->name('wxConfig.index');
    Route::get('wx/config/edit', 'AccountController@create')->name('wxConfig.edit');
    Route::post('wx/config/store', 'AccountController@store')->name('wxConfig.store');

    Route::get('reply/subscribe', 'ReplySubscribeController@index')->name('subscribe.index');
    Route::post('reply/subscribe', 'ReplySubscribeController@store')->name('subscribe.store');
    Route::resource('reply/nomatch', 'ReplyNomatchController');
    Route::resource('reply/custom', 'ReplyCustomController');

    Route::resource('menu/default', 'MenuDefaultController');
    Route::post('menu/default/delete', 'MenuDefaultController@delete');
    Route::post('menu/default/sync', 'MenuDefaultController@sync');
    Route::resource('menu/conditional', 'MenuConditionalController');
    Route::post('menu/conditional/delete', 'MenuConditionalController@delete');
    Route::post('menu/conditional/sync', 'MenuConditionalController@sync');

    Route::resource('media/{type}/cate', 'MediaCateController');
    Route::resource('media/image', 'MediaImageController');
    Route::resource('media/article', 'MediaArticleController');
    Route::resource('media/news', 'MediaNewsController');

    Route::post('media/image/upload', 'MediaImageController@upload');
    Route::any('media/news/image/upload/ue', 'MediaNewsController@uploadByUE');
    Route::post('media/news/image/choose', 'MediaNewsController@imageChoose');
    Route::post('media/image/list', 'MediaImageController@getList');
    Route::post('media/news/list', 'MediaNewsController@getList');
});
