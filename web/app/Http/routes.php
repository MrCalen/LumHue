<?php

Route::group(['prefix' => 'api'], function () {
    Route::get('/', 'HomeController@GetBridge');

    Route::get('/lights', 'HomeController@GetLights');

    Route::post('/lights', 'HomeController@SetLights');

    Route::post('/signup', 'UserController@Signup');
    Route::post('/signin', 'UserController@SignIn');

});

Route::group(['middleware' => ['web']], function () {
    //
});
