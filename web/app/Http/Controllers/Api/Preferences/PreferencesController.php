<?php

namespace App\Http\Controllers\Api\Preferences;

use App\Http\Controllers\Controller;
use App\Models\Light;
use Illuminate\Http\Request;
use MeetHue;
use MongoHue;
use JWTAuth;

class PreferencesController extends Controller
{
    /**
     * @param Request $request
     * @return string
     *
     * @SWG\Post(
     *     path="/api/preferences/chat",
     *     description="Remove chat popup for User",
     *     produces={"application/json"},
     *     tags={"preferences"},
     *     @SWG\Response(
     *         response=200,
     *         description=""
     *     ),
     * )
     */
    public function chat(Request $request)
    {
        $user = $this->tokenToUser($request);

        MongoHue::table('user_settings')->updateOne([
            'user_id' => $user->id,
        ], [
            '$set' => [
                'prefs.chat' => false,
            ],
        ], [
            'upsert' => true,
        ]);

        return Response::json(['success' => true ]);
    }
}
