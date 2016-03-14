<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Auth;
use View;
use Redirect;

class LoginController extends \App\Http\Controllers\Controller
{

  public function LoginPage()
  {
    if (Auth::user())
        return Redirect::to('lights');

    return View::make('login/login', [
    ]);
  }

  public function Authenticate(Request $request)
  {
    if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $request->input('remember')))
      return Redirect::to('lights');

    return Redirect::to('login');
  }
}

?>
