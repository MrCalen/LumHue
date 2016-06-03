<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Auth;
use View;

class ProfileController extends Controller
{
    public function index()
    {
        return View::make('profile/profile');
    }
}
