<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use HueMail;
use JWTAuth;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('landing/landing');
    }

    public function home()
    {
      return View::make('home/home', [
        'acces_token_user' => $this->getToken(),
      ]);
    }
}
