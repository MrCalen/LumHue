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
    return View::make('login/login', [
      'coucou' => 'coucou12'
    ]);
  }

  public function authenticate(Request $request)
  {
    if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $request->input('remember')))
      return Redirect::to('lights');

    return Redirect::to('login');
  }
}

?>
