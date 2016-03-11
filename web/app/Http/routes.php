<?php

Route::group(['prefix' => 'api'], function () {

  /*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  | Most API Routes are protected by the API middleware which uses JWAuth
  | to generate tokens and filter them.
  |
  */
  Route::group(['middleware' => ['api']], function () {

    // MeetHue direct responses
    Route::group(['prefix' => 'meethue'], function () {
      Route::get('/bridge', 'Api\MeetHue\MeetHueController@GetBridge');
    });

    Route::get('/bridge', 'Api\LightsController@GetBridge');
    Route::post('/light', 'Api\LightsController@SetLights');

    Route::get('/lights', 'Api\LightsController@GetLights');

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
Route::group(['middleware' => ['web']], function () {
  Route::get('/', 'HomeController@index');
  Route::get('/login', 'App\LoginController@LoginPage');
  Route::post('/login', 'App\LoginController@Authentication');
  Route::get('/mongo', 'MongoDBController@GetTable');

  Route::get('/chat', 'Chat\ChatController@index');
});
