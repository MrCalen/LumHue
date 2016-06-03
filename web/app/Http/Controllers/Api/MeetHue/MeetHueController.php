<?php

namespace App\Http\Controllers\Api\MeetHue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use View;
use DB;
use JWTAuth;
use Response;

class MeetHueController extends Controller
{
    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Get(
     *     path="/api/meethue/bridge",
     *     description="Returns the bridge status from meethue",
     *     produces={"application/json"},
     *     tags={"meethue"},
     *     @SWG\Response(
     *         response=200,
     *         description=""
     *     ),
     * )
     */
    public function getBridge(Request $request)
    {
        $user = $this->tokenToUser($request);
        $token = $user->meethue_token;
        return MeetHue::getBridge($token);
    }
}
