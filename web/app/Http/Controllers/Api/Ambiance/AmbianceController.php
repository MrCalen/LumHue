<?php

namespace App\Http\Controllers\Api\Ambiance;

use App\Helpers\LumHueColorConverter;
use App\Http\Controllers\Controller;
use App\Jobs\JobAmbiance;
use Auth;
use Illuminate\Http\Request;
use MongoHue;
use Response;

class AmbianceController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $result = MongoHue::find('ambiance');
        foreach ($result as $key => $value) {
            $ambiance = $value->ambiance;
            foreach ($ambiance->lights as $ambiancelights) {
              $duration = $ambiancelights->duration;
              foreach ($ambiancelights->lightscolors as $light) {
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

        return Response::json(['success' => true,]);
    }

    public function update(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $ambiance_id = $request->get('ambiance_id');
        $ambiance = $request->get('ambiance');
        if (!$ambiance_id || !$ambiance) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        $ambianceId = new \MongoDB\BSON\ObjectID($ambiance_id);
        MongoHue::table('ambiance')->updateOne([
            'user_id' => $user_id,
            '_id' => $ambianceId,
        ], [
            '$set' => [
                'user_id' => $user_id,
                'ambiance' => $ambiance,
            ],
        ], [
            'upsert' => true,
        ]);
        return Response::json('{ "success" : true }');
    }

    public function remove(Request $request) {
        $user_id = $this->tokenToUser($request);

        $ambiance_id = $request->get('ambiance_id');
        if (!$ambiance_id) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        $ambianceId = new \MongoDB\BSON\ObjectID($ambiance_id);

        $user_id = $this->tokenToUser($request);
        MongoHue::table('ambiance')->removeOne([
            'user_id' => $user_id,
            '_id' => $ambianceId,
        ]);

        return Response::json('{ "success" : true }');
    }

    public function apply(Request $request)
    {
        $ambiance_id = $request->get('ambiance_id');
        if (!$ambiance_id) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        $this->dispatch((new JobAmbiance($ambiance_id, $this->tokenToUser($request))));
        return Response::json([
            'success' => true,
        ]);
    }
}
