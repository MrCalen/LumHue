<?php


namespace App\Http\Controllers\Editor;

use App\Helpers\Mongo\Utils;
use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\Http\Controllers\Controller;
use View;
use Auth;

class EditorController extends Controller
{
    public function index()
    {
        $data = MongoHueWrapper::findEditor(Auth::user()->id);
        $data = $data ?? [];
        $data = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($data));
        $data = json_decode($data);
        
        return View::make('editor/editor', [
            'token' => \JWTAuth::fromUser(Auth::user()),
            'data' => $data,
        ]);
    }
}
