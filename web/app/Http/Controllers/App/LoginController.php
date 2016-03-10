<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use View;

class LoginController extends \App\Http\Controllers\Controller
{

  public function LoginPage()
  {
    return View::make('login/login', [
      'coucou' => 'coucou12'
    ]);
  }
}

?>
