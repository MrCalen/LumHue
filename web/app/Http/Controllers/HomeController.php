<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use MeetHue;
use View;
use DB;

class HomeController extends Controller
{
    public function GetBridge()
    {
        $token = 'WGF4TXNzVUtJWXRrVGFSQXhlcWNrenhobk16UkIvRGgwNDJ6RmJydVhsWT0%3D';
        return MeetHue::getBridge($token);
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

    public function index()
    {
      return View::make('welcome');
    }

}
