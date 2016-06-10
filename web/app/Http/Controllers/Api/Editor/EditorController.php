<?php


namespace App\Http\Controllers\Api\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\MongoHueModel\MongoHueWrapper;
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
}
