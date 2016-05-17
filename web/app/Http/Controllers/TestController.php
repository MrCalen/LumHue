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
use App\Helpers\WebServices\SpeechApiHelper;
use App\Helpers\WebServices\LuisApiHelper;

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

    public function voice()
    {
        $content = file_get_contents('/tmp/1462668868.sampled.wav');
        $result = SpeechApiHelper::SendBinary($content);
    }

    public function luis()
    {
        $result = LuisApiHelper::GetIntent('mets la deuxieme lampe en bleu');
        dd("finished");
    }

    public function design()
    {
        return View::make('templates/basic_nav');
    }
}
