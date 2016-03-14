<?php
namespace App\Http\Controllers\Api;

use App\Helpers\MongoHueModel\MongoHueWrapper;
use app\Models\HueLight;
use App\Models\Light;
use Illuminate\Http\Request;
use App\Helpers\Mongo\Utils;
use MeetHue;
use MongoHue;
use JWTAuth;
use App\QueryBuilder\LightQueryBuilder;

class LightsController extends \App\Http\Controllers\Controller
{
  public function SetLights(Request $request)
  {
    $light_id = $request->get('id');
    $light_on = $request->get('on');
    $light_bri = $request->get('bri');
    $light_effect = $request->get('effect');

//    $light = new HueLight();
    $meethue_token = $this->getMeetHueToken($request);

  //  MeetHue::applyLightStatus($light, $meethue_token);

  }

  public function GetBridge(Request $request)
  {
    $user = $this->tokenToUser($request);
    $bridge = MongoHue::table('bridge')->find([ 'user_id' => $user->id ]);
    $bridge = Utils::MongoArray($bridge);
    //dd($bridge);
    return $bridge;
  }

  public function GetLights(Request $request)
  {
    //dd($request);
    $user = $this->tokenToUser($request);
    $light = MongoHueWrapper::RetrieveLight($user->id, 2);
    $queryBuilder = LightQueryBuilder::create($light)->setProperty('on', true);
    return $light;
    //dd($queryBuilder);
  }

}
