<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use HueMail;
use JWTAuth;

class TestController extends Controller
{
    public function mail()
    {
        HueMail::sendMail('pl.dagues@gmail.com', 'Calen', 'Test!', 'Test');
    }

    public function slack()
    {
        5 / 0;
    }
}
