<?php

namespace App\Http\Controllers\Api\MeetHue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use JWTAuth;

class MeetHueController extends Controller
{
    public function getBridge(Request $request)
    {
        $user = $this->tokenToUser($request);
        $token = $user->meethue_token;
        return MeetHue::getBridge($token);
    }
}
