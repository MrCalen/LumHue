<?php

namespace App\Http\Controllers;

use MongoHue;
use Illuminate\Http\Request;

class MongoDBController extends Controller
{
    public function getTable($table)
    {
        return MongoHue::table($table);
    }

}
