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

        return json_encode(['success' => true ]);
    }
}
