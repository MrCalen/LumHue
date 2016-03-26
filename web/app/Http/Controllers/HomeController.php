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

class HomeController extends Controller
{
    public function index()
    {
        return View::make('landing/landing');
    }

    public function mail()
    {
        HueMail::sendMail('pl.dagues@gmail.com', 'Calen', 'Test!', 'Test');
    }

    public function home()
    {
      return View::make('home/home', [
        'acces_token_user' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJkYWd1ZXNfcEB5YWthLmVwaXRhLmZyIiwiaXNzIjoiaHR0cDpcL1wvY2FsZW4ubXItY2FsZW4uZXVcL2FwaVwvc2lnbmluIiwiaWF0IjoxNDU3NjE0OTQ5LCJleHAiOjE0NjAyMDY5NDksIm5iZiI6MTQ1NzYxNDk0OSwianRpIjoiNzU2MzE4MjI5YTA1MDEyYjI4NzEwN2Y5Yzc3YTFmMDUifQ.mVtm-tqU4FpMOaHeKcDukuGPN9xW03YhFVeUCjquvlc',
      ]);
    }
}
