<?php
namespace App\Http\Controllers;

use App\Models\Light;
use Illuminate\Http\Request;
use MeetHue;


class LightsController extends Controller
{
    public function SetLights(Request $request)
    {
        $light_id = $request->get('id');
        $light_on = $request->get('on');
        $light_bri = $request->get('bri');
        $light_effect = $request->get('effect');

        $light = new Light($light_id, $light_on, $light_bri, $light_effect, null);

        MeetHue::applyLightStatus($light);
    }
}