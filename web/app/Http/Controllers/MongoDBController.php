<?php

namespace App\Http\Controllers;

use MongoHue;
use Illuminate\Http\Request;

class MongoDBController extends Controller
{
    public function getTable(Request $request)
    {
        return MongoHue::table($request->get("table"));
    }

    public function insertIntoTable($table, $element)
    {
        return MongoHue::table($table)->insert($element);
    }

    public function GetBridge()
    {
    //    $mongo = new LumHueMongo();
  //      return $mongo->getCollection();
    }
}
