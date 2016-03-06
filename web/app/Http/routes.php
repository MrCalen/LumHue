<?php

Route::group(['prefix' => 'api'], function () {
    Route::get('/', 'HomeController@GetBridge');

    Route::post('/signup', 'UserController@Signup');
    Route::post('/signin', 'UserController@SignIn');

    Route::post('/light', 'LightsController@SetLights');
});

Route::group(['middleware' => ['web']], function () {
    //
});
