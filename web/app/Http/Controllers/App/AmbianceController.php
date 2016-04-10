<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Auth;
use View;

class AmbianceController extends Controller
{
    public function index()
    {
        return View::make('ambiances/ambiance', [
            'token' => \JWTAuth::fromUser(Auth::user()),
        ]);
    }
}