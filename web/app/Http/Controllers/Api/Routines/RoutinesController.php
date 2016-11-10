<?php

namespace App\Http\Controllers\Api\Routines;

use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\Http\Controllers\Controller;
use App\Routines\RoutinesManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use MongoHue;

class RoutinesController extends Controller
{
    public function get()
    {
        $routines = MongoHueWrapper::getRoutines(true);
        return new JsonResponse($routines);
    }

    public function edit(Request $request)
    {
        $routine = $request->only('routine');
        if (!$routine) {
            return new JsonResponse([
                'error' => true,
                'message' => 'routine field not provided'
            ], 400);
        }

        $routine = $routine['routine'];
        RoutinesManager::updateRoutine($routine);
        return new JsonResponse([
            'error' => false,
            'routine' => $routine,
        ]);
    }
}