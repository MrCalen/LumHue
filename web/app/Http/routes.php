<?php

Route::group(['prefix' => 'api'], function ()
{

  /*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  | Most API Routes are protected by the API middleware which uses JWAuth
  | to generate tokens and filter them.
  |
  */
  Route::group(['middleware' => ['api']], function (){

    Route::get('/', 'HomeController@GetBridge');
    Route::post('/light', 'LightsController@SetLights');

  });

  // Token Generation routes.
  Route::post('/signup', 'UserController@Signup');
  Route::post('/signin', 'UserController@SignIn');

});


/*
|--------------------------------------------------------------------------
| Web App Routes
|--------------------------------------------------------------------------
| Routes for Web App to create views.
| Protected by CSRF verification and Web MiddleWare.
|
*/
Route::group(['middleware' => ['web']], function ()
{
  //
  Route::get('/', 'HomeController@index');
  Route::get('/login', 'App\LoginController@LoginPage');
  Route::get('/mongo', 'MongoDBController@GetTable');
});
