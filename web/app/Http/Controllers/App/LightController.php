<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Auth;
use View;

class LightController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return View::make('lights/light', [
            'token' => \JWTAuth::fromUser(Auth::user()),
        ]);
    }
}
