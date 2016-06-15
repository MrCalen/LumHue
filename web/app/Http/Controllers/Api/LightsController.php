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
use Response;
use App\QueryBuilder\LightQueryBuilder;
use App\Helpers\LumHueColorConverter;

/**
 * Class LightsController
 * @package App\Http\Controllers\Api
 *
 */
class LightsController extends Controller
{
    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Post(
     *     path="/api/lights",
     *     description="Apply treatment to light",
     *     produces={"application/json"},
     *     tags={"lights"},
     *     @SWG\Response(
     *         response=200,
     *         description="Apply treatment to light"
     *     ),
     * )
     */
    public function setLights(Request $request)
    {
        $light_id = $request->get('id');
        $light_on = $request->get('on') === 'true' || $request->get('on') === true;
        $light_effect = $request->get('effect');
        $light_color = $request->get('color');
        if (!$light_id || !$light_color) {
            return Response::json("Missing parameter", 500);
        }

        $light_color = LumHueColorConverter::RGBstrToRGB($light_color);

        $hueColors = LumHueColorConverter::RGBtoChromatic($light_color[0], $light_color[1], $light_color[2]);

        $user = $this->tokenToUser($request);
        Logger::log('Applying action on light ' . $light_id, $user->id, $user->name);

        $queryBuilder = LightQueryBuilder::create(strval($light_id), $user->meethue_token);
        $queryBuilder->setProperty('on', $light_on)
                     ->setProperty('bri', $hueColors['bri'])
                     ->setProperty('xy', [
                       $hueColors['x'],
                       $hueColors['y'],
                     ])
                     ->setProperty('effect', $light_effect);

        $queryBuilder->apply();

        return Response::json([
            'status' => 'OK',
        ]);
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/bridge",
     *     description="Get Bridge status",
     *     produces={"application/json"},
     *     tags={"lights"},
     *     @SWG\Response(
     *         response=200,
     *         description="Get bridge status"
     *     ),
     * )
     */
    public function getBridge(Request $request)
    {
        $meethue = $this->getMeetHueToken($request);
        if (!$meethue) {
            return Response::json([
                'light' => [],
                'status' => 'KO',
            ]);
        }
        $bridge_str = LightQueryBuilder::create(null, $meethue)->getBridgeState();
        $bridge = json_decode($bridge_str);
        if (!$bridge) {
            Logger::error($bridge_str, "user");
            return Response::json("Error", 500);
        }
        foreach ($bridge->lights as $light) {
            $rgb = LumHueColorConverter::chromaticToRGB($light->state->xy[0], $light->state->xy[1], $light->state->bri);
            $light->rgb = $rgb;
            $light->rgbstr = 'rgb(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ')';
            $light->rgbhex = LumHueColorConverter::RGBToHex($rgb['r'], $rgb['g'], $rgb['b']);
        }

        return Response::json($bridge);
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/lights",
     *     description="Get Light status as Array",
     *     produces={"application/json"},
     *     tags={"lights"},
     *     @SWG\Response(
     *         response=200,
     *         description="Get Light status as Array"
     *     ),
     * )
     */
    public function getLights(Request $request)
    {
        $bridge = json_decode($this->getBridge($request)->content());
        $lights = array();
        foreach ($bridge->lights as $light) {
            $lights[] = $light;
        }
        return Response::json($lights);
    }
}
