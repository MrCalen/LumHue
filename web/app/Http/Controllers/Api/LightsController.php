<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Light;
use App\Models\Logger;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use JWTAuth;
use Auth;
use App\QueryBuilder\LightQueryBuilder;
use App\Helpers\LumHueColorConverter;

class LightsController extends Controller
{
    public function setLights(Request $request)
    {
        $light_id = $request->get('id');
        $light_on = $request->get('on') == 'true';
        $light_effect = $request->get('effect');
        $light_color = $request->get('color');
        $light_color = LumHueColorConverter::RGBstrToRGB($light_color);

        $hueColors = LumHueColorConverter::RGBtoChromatic($light_color[0], $light_color[1], $light_color[2]);

        $user = $this->tokenToUser($request);
        Logger::Log('Applying action on light ' . $light_id, $user->id, $user->name);

        $queryBuilder = LightQueryBuilder::create(strval($light_id), $user->meethue_token);
        $queryBuilder->setProperty('on', $light_on)
                     ->setProperty('bri', $hueColors['bri'])
                     ->setProperty('xy', [
                       $hueColors['x'],
                       $hueColors['y'],
                     ])
                     ->setProperty('effect', $light_effect);

        $queryBuilder->apply();
    }

    public function getBridge(Request $request)
    {
        $meethue = $this->getMeetHueToken($request);
        $bridge = LightQueryBuilder::create(null, $meethue)->getBridgeState();
        $bridge = json_decode($bridge);
        foreach ($bridge->lights as $light) {
            $rgb = LumHueColorConverter::chromaticToRGB($light->state->xy[0], $light->state->xy[1], $light->state->bri);
            $light->rgb = $rgb;
            $light->rgbstr = 'rgb(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ')';
            $light->rgbhex = LumHueColorConverter::RGBToHex($rgb['r'], $rgb['g'], $rgb['b']);
        }

        return json_encode($bridge);
    }

    public function getLights(Request $request)
    {
        $bridge = json_decode($this->getBridge($request));
        $lights = array();
        foreach($bridge->lights as $light) {
            $lights[] = $light;
        }
        return json_encode($lights);
    }
}
