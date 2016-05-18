<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Auth;
use View;

class AppController extends Controller
{
    public function index()
    {
        return View::make('templates/basic_nav', [
            'token' => \JWTAuth::fromUser(Auth::user()),
        ]);
    }
}