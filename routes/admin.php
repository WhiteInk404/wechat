<?php

Route::get('/', 'HomeController@index');
Route::resource('users', 'UsersController', ['only' => ['index']]);

/* 活动管理 */
Route::resource('activities', 'ActivityController');
