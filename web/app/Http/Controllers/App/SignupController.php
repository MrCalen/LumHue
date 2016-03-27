<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Redirect;
use Hash;
use JWTAuth;
use App\User;
use Exception;
use URL;
use HueMail;

class SignupController extends Controller
{
    public function signUpPage() {
        if (Auth::user()) {
            //   return Redirect::to('lights');
            Auth::logout();
        }

        return View::make('signup/signup', []);
    }

    public function signup(Request $request) {
        try {
            $user = User::create($request->all());
            $user->password = Hash::make($request->input('password'));
            $validation_token = Hash::make(substr(str_shuffle(MD5(microtime())), 0, 16));
            $user->validation_token = $validation_token;
            $user->save();
            $validation_link = URL::to('signup/confirm') . '?token=' . $validation_token;
            HueMail::sendConfirmation($user->name, $user->email, $validation_link);

        } catch (Exception $e) {
            // FIXME: Handle here
            dd($e->getMessage());
        }

        return Redirect::to('login');
    }

    public function confirm(Request $request) {
        $confirmation = $request->input('token');
        if (!$confirmation)
            // FIXME: Handle here
            dd('FIXME');
        $user = User::where('validation_token', '=', $confirmation)->first();
        if (!$user)
            // FIXME: Handle here
            dd('FIXME');
        $user->validation_token = null;
        $user->save();

        return Redirect::to('login');
    }
}
