<?php

namespace App\Http\Controllers\Api\Ambiance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoHue;
use App\Helpers\Mongo\Utils;
use App\Helpers\LumHueColorConverter;
use Response;

class AmbianceController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $result = MongoHue::find('ambiance');
        foreach ($result as $key => $value) {
            $ambiance = $value->ambiance;
            foreach ($ambiance->lights as $light) {
                $light_color = LumHueColorConverter::RGBstrToRGB($light->color);
                $r = $light_color[0];
                $g = $light_color[1];
                $b = $light_color[2];

                $light->rgbhex = LumHueColorConverter::RGBToHex($r, $g, $b);
            }
        }

        return Response::json($result);
    }

    public function create(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $ambiance = $request->get('ambiance');
        if (!$ambiance) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        //$ambiance = json_decode($ambiance);
        MongoHue::insert('ambiance', [
                'ambiance' => $ambiance,
                'user_id' => $user_id->id,
            ]);

        return Response::json([ 'success' => true, ]);
    }
}
