<?php

namespace App\Http\Controllers\Api\Ambiance;

use App\Helpers\LumHueColorConverter;
use App\Http\Controllers\Controller;
use App\Jobs\JobAmbiance;
use App\Models\Logger;
use Auth;
use Illuminate\Http\Request;
use MongoHue;
use Response;

/**
 * Class AmbianceController
 * @package App\Http\Controllers\Api\Ambiance
 *
 * @SWG\Info(
 *     title="LumHue",
 *     version="1.0.0"
 * )
 */
class AmbianceController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     *
     * @SWG\Get(
     *     path="/api/ambiance",
     *     description="Returns the list of lights (with color converted).",
     *     produces={"application/json"},
     *     tags={"ambiances"},
     *     @SWG\Response(
     *         response=200,
     *         description="List of lights"
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $user_id = $this->tokenToUser($request);
        $result = MongoHue::find('ambiance', [
            'user_id' => $user_id->id,
        ]);
        foreach ($result as $key => $value) {
            $ambiance = $value->ambiance;
            foreach ($ambiance->lights as $ambiancelights) {
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

    /**
     * @param Request $request
     * @return mixed
     *
     * @SWG\Post(
     *     path="/api/ambiance/create",
     *     description="Create a new ambiance.",
     *     produces={"application/json"},
     *     tags={"ambiances"},
     *     @SWG\Response(
     *         response=200,
     *         description="if the operation succeeded"
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $user = $this->tokenToUser($request);
        $ambiance = $request->get('ambiance');
        if (!$ambiance) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        MongoHue::insert('ambiance', [
            'ambiance' => $ambiance,
            'user_id' => $user->id,
        ]);
        Logger::log('Created ambiance ', $user->id, $user->name);

        return Response::json(['success' => true,]);
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * @SWG\Post(
     *     path="/api/ambiance/udpate",
     *     description="Update an ambiance",
     *     produces={"application/json"},
     *     tags={"ambiances"},
     *     @SWG\Response(
     *         response=200,
     *         description="if the operation succeeded"
     *     ),
     * )
     */
    public function update(Request $request)
    {
        $user = $this->tokenToUser($request);
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
            'user_id' => $user->id,
            '_id' => $ambianceId,
        ], [
            '$set' => [
                'user_id' => $user->id,
                'ambiance' => $ambiance,
            ],
        ], [
            'upsert' => true,
        ]);
        Logger::log('Update ambiance', $user->id, $user->name);
        return Response::json('{ "success" : true }');
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * @SWG\Post(
     *     path="/api/ambiance/remove",
     *     description="Deletes an ambiance",
     *     produces={"application/json"},
     *     tags={"ambiances"},
     *     @SWG\Response(
     *         response=200,
     *         description="If the operation succeeded."
     *     ),
     * )
     */
    public function remove(Request $request)
    {
        $user = $this->tokenToUser($request);

        $ambiance_id = $request->get('ambiance_id');
        if (!$ambiance_id) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }

        $ambianceId = new \MongoDB\BSON\ObjectID($ambiance_id);
        Logger::log('Deleted ambiance', $user->id, $user->name);

        $user = $this->tokenToUser($request);
        MongoHue::table('ambiance')->deleteOne([
            'user_id' => $user->id,
            '_id' => $ambianceId,
        ]);

        return Response::json('{ "success" : true }');
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * @SWG\Post(
     *     path="/api/ambiance/apply",
     *     description="Applies the ambiance to lights",
     *     produces={"application/json"},
     *     tags={"ambiances"},
     *     @SWG\Response(
     *         response=200,
     *         description="if the operation succeeded."
     *     ),
     * )
     */
    public function apply(Request $request)
    {
        $ambiance_id = $request->get('ambiance_id');
        if (!$ambiance_id) {
            return Response::json([
                'error' => 'Please provide an ambiance',
                'success' => false,
            ]);
        }
        $user = $this->tokenToUser($request);
        Logger::log('Applyied ambiance ' . $ambiance_id, $user->id, $user->name);
        $this->dispatch((new JobAmbiance($ambiance_id, $this->tokenToUser($request))));
        return Response::json([
            'success' => true,
        ]);
    }
}
