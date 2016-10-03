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


        Route::get('/user', 'Api\UserController@tokenToUser');

        // MeetHue direct responses
        Route::group(['prefix' => 'meethue'], function () {
            Route::get('/bridge', 'Api\MeetHue\MeetHueController@getBridge');
        });

        // Get Bridge Status
        Route::get('/bridge', 'Api\LightsController@getBridge');
        Route::get('/lights', 'Api\LightsController@getLights');
        Route::post('/lights', 'Api\LightsController@setLights');

        // Ambiances
        Route::group(['prefix' => 'ambiance'], function () {
            Route::get('/', 'Api\Ambiance\AmbianceController@index');
            Route::post('/create', 'Api\Ambiance\AmbianceController@create');
            Route::post('/apply', 'Api\Ambiance\AmbianceController@apply');
            Route::post('/update', 'Api\Ambiance\AmbianceController@update');
            Route::post('/remove', 'Api\Ambiance\AmbianceController@remove');
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/light', 'Api\dashboard\StatsController@light');
            Route::get('/lights', 'Api\dashboard\StatsController@lights');
            Route::get('/bridge', 'Api\dashboard\StatsController@bridge');
            Route::get('/history', 'Api\dashboard\StatsController@history');
            Route::get('/weather', 'Api\dashboard\StatsController@weather');
        });

        Route::group(['prefix' => 'preferences'], function () {
            Route::post('/chat', 'Api\Preferences\PreferencesController@chat');
        });

        Route::group(['prefix' => 'editor'], function () {
            Route::post('/save', 'Api\Editor\EditorController@save');
        });

        Route::group(['prefix' => 'beacons'], function () {
            Route::get('/all', 'Api\Editor\EditorController@allBeacons');
            Route::post('/sync', 'Api\Editor\EditorController@syncBeacon');
        });

    });

    // Token Generation routes.
    Route::post('/signup', 'Api\UserController@signup');
    Route::post('/signin', 'Api\UserController@signIn');
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
    Route::get('/logout', 'App\LoginController@logout');

    // Signup routes
    Route::group(['prefix' => 'signup'], function () {
        Route::get('/', 'App\SignupController@signUpPage');
        Route::post('/', 'App\SignupController@signup');
        Route::get('/confirm', 'App\SignupController@confirm');

        Route::post('/forgot', 'App\SignupController@reset');
        Route::post('/reset/password', 'App\SignupController@resetPasswordPost');
        Route::get('/reset/index', 'App\SignupController@resetPwd');
    });


    // Authenticated routes
    Route::group(['middleware' => 'auth', 'prefix' => 'app'], function () {
        Route::get('/', 'App\AppController@index');
    });

    Route::group(['middleware' => 'auth', 'prefix' => 'editor'], function () {
        Route::get('/', 'Editor\EditorController@index');
        Route::post('/save', 'Editor\EditorController@save');
    });

    Route::group(['prefix' => 'test'], function () {
        Route::get('email', 'TestController@mail');
        Route::get('slack', 'TestController@slack');
        Route::get('voice', 'TestController@voice');
        Route::get('luis', 'TestController@luis');
        Route::get('/', 'TestController@design');
    });
});
