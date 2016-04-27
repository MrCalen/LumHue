<?php


namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use View;

class DashboardController extends Controller
{
    public function index() {
        return View::make('dashboard/dashboard', [
            'token' => $this->userToToken(),
        ]);
    }
}