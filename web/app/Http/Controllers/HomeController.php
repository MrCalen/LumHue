<?php

namespace App\Http\Controllers;
use Request;
use MeetHue;
use DB;

class HomeController extends Controller
{
    public function GetBridge()
    {
        return MeetHue::getBridge();
    }

    public function GetLights()
    {
        return DB::table('lights')->get();
    }

    public function SetLights(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');

        DB::table('lights')
          ->where('lights.id', $id)
          ->update([ 'on' => $status ]);

        return DB::table('lights')->get();
    }

}
