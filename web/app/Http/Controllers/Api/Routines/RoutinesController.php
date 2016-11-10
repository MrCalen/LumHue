<?php

namespace App\Http\Controllers\Api\Routines;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use MongoHue;

class RoutinesController extends Controller
{
    public function get()
    {
        $routines = MongoHue::getRoutines();
        return new JsonResponse($routines);
    }
}