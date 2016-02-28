<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function GetBridge()
    {
      $result = file_get_contents('https://www.meethue.com/api/getbridge?token=alhzT29HZFptWEFtTTlNaTVGYlYvUHRCVHJHYjNPUWF3S2NSTjY0Znc3ND0%3D&bridgeid=001788fffe260c4a');
      return ($result);
    }

    public function GetLights()
    {
        return \DB::table('lights')->get();
    }

    public function SetLights(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');

        \DB::table('lights')
          ->where('lights.id', $id)
          ->update([ 'on' => $status ]);

        return \DB::table('lights')->get();
    }

}
