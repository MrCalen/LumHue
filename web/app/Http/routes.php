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
      Route::get('/bridge', 'Api\MeetHue\MeetHueController@getBridge');
    });

    // Get Bridge Status
    Route::get('/bridge', 'Api\LightsController@getBridge');

    Route::get('/lights', 'Api\LightsController@getLights');
    Route::post('/lights', 'Api\LightsController@setLights');
  });

  // Token Generation routes.
  Route::post('/signup', 'UserController@signup');
  Route::post('/signin', 'UserController@signIn');

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
  Route::get('/', 'HomeController@index');

  // Login routes
  Route::group(['prefix' => 'login'], function () {
    Route::get('/', 'App\LoginController@loginPage');
    Route::post('/', 'App\LoginController@authenticate');
  });

  // Chat route
  Route::get('/chat', 'Chat\ChatController@index');

  // Authentificated routes
  Route::group(['middleware' => 'auth'], function()  {
    Route::get('/lights', 'App\LightController@index');
  });

  Route::group(['prefix' => 'test'], function () {
    Route::get('email', 'TestController@mail');
    Route::get('slack', 'TestController@slack');
  });

  Route::get('/mongo', 'MongoDBController@GetTable');
  Route::get('/homeview', 'HomeController@home');

});
