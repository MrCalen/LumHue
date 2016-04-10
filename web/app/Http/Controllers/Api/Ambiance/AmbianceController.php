<?php

namespace App\Http\Controllers\Api\Ambiance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoHue;
use App\Helpers\Mongo\Utils;
use Response;

class AmbianceController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $result = MongoHue::find('ambiance');
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
