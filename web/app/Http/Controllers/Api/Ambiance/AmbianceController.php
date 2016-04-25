<?php

namespace App\Http\Controllers\Api\Ambiance;

use App\Http\Controllers\Controller;
use App\Jobs\JobAmbiance;
use app\Models\HueLight;
use App\QueryBuilder\LightQueryBuilder;
use Illuminate\Http\Request;
use MongoHue;
use App\Helpers\Mongo\Utils;
use App\Helpers\LumHueColorConverter;
use Response;
use Auth;

class AmbianceController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $result = MongoHue::find('ambiance');
        foreach ($result as $key => $value) {
            $ambiance = $value->ambiance;
            foreach ($ambiance->lights as $step) {
                foreach ($step->lightscolors as $light) {
                    $light_color = LumHueColorConverter::RGBstrToRGB($light->color);
                    $r = $light_color[0];
                    $g = $light_color[1];
                    $b = $light_color[2];

                    $light->rgbhex = LumHueColorConverter::RGBToHex($r, $g, $b);
                }
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

        MongoHue::insert('ambiance', [
                'ambiance' => $ambiance,
                'user_id' => $user_id->id,
            ]);

        return Response::json([ 'success' => true, ]);
    }

    public function apply(Request $request) {
        $ambiance_id = $request->get('ambiance_id');
        if (!$ambiance_id) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        $this->dispatch((new JobAmbiance($ambiance_id, $this->tokenToUser($request))));
        return Response::json('{ "status" : true  }');
    }
}
