<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use JWTAuth;

class HomeController extends Controller
{
    public function index()
    {
      return View::make('welcome');
    }

    public function test()
    {
      return View::make('test/test');
    }
}
