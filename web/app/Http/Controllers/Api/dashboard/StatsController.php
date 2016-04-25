<?php


namespace App\Http\Controllers\Api\dashboard;

use App\Helpers\StatsManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class StatsController extends Controller
{
    public function light(Request $request) {
        $statsManager = new StatsManager($this->tokenToUser($request));
        return json_encode($statsManager->lightStats('hours', 1));
    }

    public function bridge(Request $request) {
        $statsManager = new StatsManager($this->tokenToUser($request));
        return json_encode($statsManager->bridgeStats('hours'));
    }

}