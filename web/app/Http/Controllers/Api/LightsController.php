<?php
namespace App\Http\Controllers\Api;

use App\Models\Light;
use Illuminate\Http\Request;
use App\Helpers\Mongo\Utils;
use MeetHue;
use MongoHue;
use JWTAuth;

class LightsController extends \App\Http\Controllers\Controller
{
  public function SetLights(Request $request)
  {
    $light_id = $request->get('id');
    $light_on = $request->get('on');
    $light_bri = $request->get('bri');
    $light_effect = $request->get('effect');

    $light = new Light($light_id, $light_on, $light_bri, $light_effect, null);
    $meethue_token = $this->getMeetHueToken($request);

    MeetHue::applyLightStatus($light, $meethue_token);

  }

  public function GetBridge(Request $request)
  {
    $user = $this->tokenToUser($request);
    $bridge = MongoHue::table('bridge')->find([ 'user_id' => $user->id ]);
    $bridge = Utils::MongoArray($bridge);

    return $bridge;
  }
}
