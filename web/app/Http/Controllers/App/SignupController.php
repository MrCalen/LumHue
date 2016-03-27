<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Redirect;

class SignupController extends Controller
{
  public function signUpPage()
  {
      if (Auth::user()) {
          return Redirect::to('lights');
      }

      return View::make('signup/signup', []);
  }

  public function signup(Request $request)
  {
    try {
        $user = User::create($request->all());
        $user->password = \Hash::make($request->input('password'));
        $user->save();
        $token = \JWTAuth::fromUser($user);
    } catch (\Exception $e) {
    }
      return Redirect::to('login');
  }
}
