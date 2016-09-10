<?php


namespace App\Http\Controllers\Api\Editor;

use App\Helpers\MongoHueModel\MongoHue;
use App\Helpers\MongoHueModel\MongoHueWrapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class EditorController extends Controller
{

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/editor/save",
     *     description="Saves the new house",
     *     produces={"application/json"},
     *     tags={"editor"},
     *     @SWG\Response(
     *         response=200,
     *         description=""
     *     ),
     * )
     */
    public function save(Request $request)
    {
        $data = $request->get('data');
        $user = $this->tokenToUser($request);
        
        MongoHueWrapper::updateEditor(json_decode($data), $user->id);

        return Response::json([
            'success' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/beacons/all",
     *     description="Get all beacons on the apartment",
     *     produces={"application/json"},
     *     tags={"beacons"},
     *     @SWG\Response(
     *         response=200,
     *         description=""
     *     ),
     * )
     */
    public function allBeacons(Request $request)
    {
        $user_id = $request->user->id;
        $editor = MongoHueWrapper::fetchAndConvertEditor($user_id);
        if (!$editor) {
            return Response::json([
                'success' => false,
                'error' => 'No editor found for the user'
            ], 404);
        }

        $items = $editor->data->items;
        $beacons = array_filter($items, function (&$elt) {
            if ($elt->item_name !== 'Beacon') {
                return false;
            }
            // Element is a beacon
            $elt->isSync = isset($elt->uid);
            return true;
        });
        return Response::json($beacons);
    }

    public function syncBeacon(Request $request)
    {
        $user_id = $request->user->id;
        if (!$request->has('beacon_id') || !$request->has('beacon_uid')) {
            return Response::json([
                'success' => false,
                'error' => 'Please provide beacon id and beacon uid',
            ], 404);
        }

        $beaconId = $request->get('beacon_id');
        $beaconUid = $request->get('beacon_uid');

        $editor = MongoHueWrapper::fetchAndConvertEditor($user_id);
        if (!$editor) {
            return Response::json([
                'success' => false,
                'error' => 'No editor found for the user'
            ], 404);
        }

        $editorObjects = $editor->data->items;
        $change = false;
        foreach ($editorObjects as $object) {
            if (isset($object->uuid) && $object->uuid === $beaconId) {
                $change = true;
                $object->beacon_uid = $beaconUid;
            }
        }
        if ($change) {
            $editor->data->items = $editorObjects;
            MongoHueWrapper::updateEditor($editor->data, $user_id);
        }

        return Response::json([
            'success' => true,
            'changed' => $change,
        ]);
    }
}
