<?php

namespace App\Http\Controllers\Api\Routines;

use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use MongoHue;

class RoutinesController extends Controller
{
    public function get()
    {
        $routines = MongoHueWrapper::getRoutines();
        return new JsonResponse($routines);
    }
}