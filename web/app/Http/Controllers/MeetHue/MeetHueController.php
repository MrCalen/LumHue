<?php

namespace App\Http\Controllers\MeetHue;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use App\User;
use JWTAuth;

class MeetHueController extends \App\Http\Controllers\Controller
{
  public function GetBridge(Request $request)
  {
    $user = $this->tokenToUser($request);
    $token = $user->meethue_token;

    $bridge = MeetHue::getBridge($token);
    \App\Helpers\MongoHueModel\MongoHueWrapper::UpdateBridgeStatus($bridge, $user->id);
  }
}
