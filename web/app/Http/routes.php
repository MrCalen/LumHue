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

    // Signup routes
    Route::group(['prefix' => 'signup'], function () {
        Route::get('/', 'App\SignupController@signUpPage');
        Route::post('/', 'App\SignupController@signup');
        Route::get('/confirm', 'App\SignupController@confirm');

        Route::post('/forgot', 'App\SignupController@reset');
        Route::post('/reset/password', 'App\SignupController@resetPasswordPost');
        Route::get('/reset/index', 'App\SignupController@resetPwd');
    });

    // Chat route
    Route::get('/chat', 'Chat\ChatController@index');

    // Authenticated routes
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/lights', 'App\LightController@index');
        Route::get('/logout', 'App\LoginController@logout');
        Route::get('/ambiances', 'App\AmbianceController@index');
        Route::get('/dashboard', 'App\DashboardController@index');
        Route::get('/profile', 'App\ProfileController@index');
    });

    Route::group(['prefix' => 'test'], function () {
        Route::get('email', 'TestController@mail');
        Route::get('slack', 'TestController@slack');
    });
});
