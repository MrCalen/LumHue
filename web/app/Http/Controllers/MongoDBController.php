<?php

namespace App\Http\Controllers;

use LumHueMongo;
use Illuminate\Http\Request;

class MongoDBController extends Controller
{
    public function getTable(Request $request)
    {
        return LumHueMongo::table($request->get("table"));
    }
    public function insertIntoTable($table, $element)
    {
        return LumHueMongo::table($table)->insert($element);
    }
    public function GetBridge()
    {
        $mongo = new LumHueMongo();
        return $mongo->getCollection();
    }
}