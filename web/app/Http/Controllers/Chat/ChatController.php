<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use JWTAuth;

class ChatController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return View::make('chat/chat');
    }
}
