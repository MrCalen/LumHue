<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Light;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use JWTAuth;
use Auth;
use App\QueryBuilder\LightQueryBuilder;

class LightsController extends Controller
{
    public function setLights(Request $request)
    {
        $light_id = $request->get('id');
        $light_on = $request->get('on');
        $light_bri = $request->get('bri');
        $light_effect = $request->get('effect');

        $user = $this->tokenToUser($request);
 #       $light = MongoHueWrapper::RetrieveLight($user->id, $light_id);
        $queryBuilder = LightQueryBuilder::create($light_id);
        $queryBuilder->setProperty('on', $light_on)
                     ->setProperty('bri', $light_bri)
                     ->setProperty('effect', $light_effect);

        $queryBuilder->apply($user->meethue_token);
    }

    public function getBridge(Request $request)
    {
        $meethue = $this->getMeetHueToken($request);
        $bridge = LightQueryBuilder::create(null, $meethue)->getBridgeState();
        return $bridge;
    }

    public function getLights(Request $request)
    {
        return $this->getBridge($request);
    }
}
