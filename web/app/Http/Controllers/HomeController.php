<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use HueMail;
use JWTAuth;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('landing/landing');
    }
}
