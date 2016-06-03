<?php

namespace App\Http\Controllers\MeetHue;

use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\Http\Controllers\Controller;

class MeetHueController extends Controller
{
    public function getBridge(Request $request)
    {
        $user = $this->tokenToUser($request);
        $token = $user->meethue_token;
        $bridge = MeetHue::getBridge($token);
        MongoHueWrapper::updateBridgeStatus($bridge, $user->id);
    }
}
