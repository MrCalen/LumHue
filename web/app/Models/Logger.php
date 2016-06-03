<?php

namespace App\Models;

use MongoHue;

class Logger
{
    public static function log($str, $user_id, $user)
    {
        date_default_timezone_set('Europe/Paris');
        MongoHue::table('history')
            ->insertOne([
                'action' => $str,
                'user_id' => $user_id,
                'user' => $user,
                'date' => date("F j, Y, g:i a"),
            ]);
    }
}
