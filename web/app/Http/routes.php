<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Most API Routes are protected by the API middleware which uses JWAuth
| to generate tokens and filter them.
|
*/
Route::group(['prefix' => 'api'], function () {
  Route::group(['middleware' => ['api']], function () {

    // MeetHue direct responses
    Route::group(['prefix' => 'meethue'], function () {
      Route::get('/bridge', 'Api\MeetHue\MeetHueController@GetBridge');
    });

    // Get Bridge Status
    Route::get('/bridge', 'Api\LightsController@GetBridge');

    Route::get('/lights', 'Api\LightsController@GetLights');
    Route::post('/light', 'Api\LightsController@SetLights');
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

  // Index
  Route::get('/', 'HomeController@Index');

  // Login routes
  Route::group(['prefix' => 'login'], function () {
    Route::get('/', 'App\LoginController@LoginPage');
    Route::post('/', 'App\LoginController@Authenticate');
  });

  // Chat route
  Route::get('/chat', 'Chat\ChatController@Index');

  // Authentificated routes
  Route::group(['middleware' => 'auth'], function()  {
    Route::get('/lights', 'App\LightController@Index');
  });

  Route::group(['prefix' => 'test'], function () {
    Route::get('email', 'HomeController@Mail');
  });

});
