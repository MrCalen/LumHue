<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Redirect;

class LoginController extends Controller
{
    public function loginPage()
    {
        if (Auth::user()) {
            return Redirect::to('lights');
        }

        return View::make('login/login', []);
    }

    public function authenticate(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'validation_token' => null,
        ];

        if (Auth::attempt($credentials, $request->input('remember'))) {
            return Redirect::to('lights');
        }

        return Redirect::to('login');
    }
}
