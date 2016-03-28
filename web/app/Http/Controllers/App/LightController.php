<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;

class LightController extends Controller
{
    public function index()
    {
        return View::make('lights/light', [
            'token' => \JWTAuth::fromUser(Auth::user()),
        ]);
    }
}
