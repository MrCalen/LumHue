<?php

Route::group(['prefix' => 'api'], function ()
{

  Route::group(['middleware' => 'api'], function (){
    Route::get('/', 'HomeController@GetBridge');
    Route::post('/light', 'LightsController@SetLights');
  });

  Route::post('/signup', 'UserController@Signup');
  Route::post('/signin', 'UserController@SignIn');

});

Route::group(['middleware' => ['web']], function ()
{
  //
});
