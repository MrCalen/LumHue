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

    public function create(Request $request)
    {
        $user = $this->tokenToUser($request);
        $routine = $request->only('routine');
        if (!$routine) {
            return new JsonResponse([
                'error' => true,
                'message' => 'routine field not provided'
            ], 400);
        }

        $routine = $routine['routine'];
        $routine['user_id'] = $user->id;
        RoutinesManager::createRoutine($routine);
        return new JsonResponse([
            'error' => false,
            'routine' => $routine,
        ]);
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

    public function delete(Request $request)
    {
        if (!$request->has('routineId')) {
            return new JsonResponse([
                'error' => true,
                'message' => 'routineId field not provided'
            ], 400);
        }

        $routineId = $request->get('routineId');
        RoutinesManager::deleteRoutine($routineId);
        return new JsonResponse([
            'error' => false,
            'message' => "Remove successful",
        ]);
    }
}