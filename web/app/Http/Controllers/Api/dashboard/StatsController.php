<?php


namespace App\Http\Controllers\Api\dashboard;

use App\Helpers\StatsManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoHue;


class StatsController extends Controller
{
    public function light(Request $request) {
        $light_id = $request->get('light_id');
        $granularity = $request->get('granularity');
        if (!$light_id || !$granularity) {
            return json_encode([
                'granularity' => $granularity,
                'light_id' => $light_id,
                'error' => 'Please provide a light id and granularity',
            ]);
        }

        $statsManager = new StatsManager($this->tokenToUser($request));
        return json_encode($statsManager->lightStats($granularity, $light_id));
    }

    public function lights(Request $request) {
        $granularity = $request->get('granularity');
        if (!$granularity) {
            return json_encode([
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
        return json_encode($stats);
    }

    public function bridge(Request $request) {
        $statsManager = new StatsManager($this->tokenToUser($request));
        return json_encode($statsManager->bridgeStats('hours'));
    }

    public function history(Request $request) {
        $statsManager = new StatsManager($this->tokenToUser($request));
        $records = iterator_to_array($statsManager->history());
        return json_encode($records);
    }

    public function weather(Request $request) {
        $lat = $request->get('lat');
        $long = $request->get('long');

        $statsManager = new StatsManager($this->tokenToUser($request));
        $json = $statsManager->weather($lat, $long);
        $json_dec = json_decode($json);
        $json_dec->list = array_values(array_filter($json_dec->list, function ($elt) {
            return (preg_match("/.* 15.*/", $elt->dt_txt)) === 1;
        }));
        return json_encode($json_dec);
    }
}