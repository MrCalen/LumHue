<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use JWAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function tokenToUser(\Illuminate\Http\Request $request)
    {
        $token = $this->getToken($request);
        \JWTAuth::setToken($token);
        $user = \JWTAuth::toUser();
        $user->token = $token;
        return $user;
    }

    protected function userToToken()
    {
        $user = \Auth::user();
        $token = \JWTAuth::fromUser($user);
        return $token;
    }

    protected function getMeetHueToken(\Illuminate\Http\Request $request)
    {
        $user = $this->tokenToUser($request);
        return $user->meethue_token;
    }

    protected function getToken(\Illuminate\Http\Request $request)
    {
        $token = $request->get('access_token');
        if (!$token) {
            $token = $request->get('?access_token');
        }
        return $token;
    }
}
