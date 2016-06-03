<?php

namespace App\Http\Controllers\Api\dashboard;

use App\Helpers\StatsManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoHue;
use Response;

/**
 * Class StatsController
 * @package App\Http\Controllers\Api\dashboard
 *
 */
class StatsController extends Controller
{
    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/dashboard/light",
     *     description="Returns the stats of a light",
     *     produces={"application/json"},
     *     tags={"dashboard"},
     *     @SWG\Response(
     *         response=200,
     *         description="Light Status depending of granularity..."
     *     ),
     * )
     */
    public function light(Request $request)
    {
        $light_id = $request->get('light_id');
        $granularity = $request->get('granularity');
        if (!$light_id || !$granularity) {
            return Response::json([
                'granularity' => $granularity,
                'light_id' => $light_id,
                'error' => 'Please provide a light id and granularity',
            ]);
        }

        $statsManager = new StatsManager($this->tokenToUser($request));
        return Response::json($statsManager->lightStats($granularity, $light_id));
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/dashboard/lights",
     *     description="Returns the stats for all lights",
     *     produces={"application/json"},
     *     tags={"dashboard"},
     *     @SWG\Response(
     *         response=200,
     *         description="Light Status depending of granularity..."
     *     ),
     * )
     */
    public function lights(Request $request)
    {
        $granularity = $request->get('granularity');
        if (!$granularity) {
            return Response::json([
                'granularity' => $granularity,
                'error' => 'Please provide a light id and granularity',
            ]);
        }
        $light_ids = [1,2,3];

        $statsManager = new StatsManager($this->tokenToUser($request));
        $stats = [];
        foreach ($light_ids as $light_id) {
            $stats[$light_id] = $statsManager->lightStats($granularity, $light_id);
        }
        return Response::json($stats);
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/dashboard/bridge",
     *     description="Returns the bridge",
     *     produces={"application/json"},
     *     tags={"dashboard"},
     *     @SWG\Response(
     *         response=200,
     *         description="Returns the full status of the bridge"
     *     ),
     * )
     */
    public function bridge(Request $request)
    {
        $statsManager = new StatsManager($this->tokenToUser($request));
        return Response::json($statsManager->bridgeStats('hours'));
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/dashboard/history",
     *     description="Returns the historic of actions",
     *     produces={"application/json"},
     *     tags={"dashboard"},
     *     @SWG\Response(
     *         response=200,
     *         description="Returns the historic of actions"
     *     ),
     * )
     */
    public function history(Request $request)
    {
        $statsManager = new StatsManager($this->tokenToUser($request));
        $records = iterator_to_array($statsManager->history());
        return Response::json($records);
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/dashboard/weather",
     *     description="Returns the weather forecast",
     *     produces={"application/json"},
     *     tags={"dashboard"},
     *     @SWG\Response(
     *         response=200,
     *         description="Returns the weather forecast"
     *     ),
     * )
     */
    public function weather(Request $request)
    {
        $lat = $request->get('lat');
        $long = $request->get('long');

        $statsManager = new StatsManager($this->tokenToUser($request));
        $json = $statsManager->weather($lat, $long);
        $json_dec = json_decode($json);
        $first = true;
        $json_dec->list = array_values(array_filter($json_dec->list, function ($elt) use (&$first) {

            $matches = [];
            preg_match("/.{4}-.{2}-(.{2}).* (.{2}).*/", $elt->dt_txt, $matches);
            $day = date("d");
            if ($day === $matches[1] && $first) {
                $first = false;
                return true;
            } elseif ($matches[2] === '15') {
                return true;
            }
            return false;
        }));
        return Response::json($json_dec);
    }
}
