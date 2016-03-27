<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use JWTAuth;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return View::make('chat/chat');
    }
}
