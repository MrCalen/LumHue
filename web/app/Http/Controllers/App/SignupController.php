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
            return Redirect::to('lights');
        }

        return View::make('signup/signup', []);
    }

    public function signup(Request $request) {
      $rules = array(
       'name'                => 'required',
       'email'               => 'required|email|unique:users',
       'password'            => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
       'passwordVerificaton' => 'required|same:password'
      );

      $validator = \Validator::make(\Input::all(), $rules);

      if ($validator->fails()) {
        $messages = $validator->messages();

       return Redirect::to('signup')
           ->withErrors($validator)
           ->withInput(\Input::except('password','passwordVerificaton'));

      } else {
        $user = User::create($request->all());
        $user->password = Hash::make($request->input('password'));
        $validation_token = Hash::make(substr(str_shuffle(MD5(microtime())), 0, 16));
        $user->validation_token = $validation_token;
        $user->save();
        $validation_link = URL::to('signup/confirm') . '?token=' . $validation_token;
        HueMail::sendConfirmation($user->name, $user->email, $validation_link);

        return Redirect::to('login');
    }
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
