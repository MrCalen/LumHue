<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Illuminate\Support\Facades\Response;

class UserController extends \App\Http\Controllers\Controller
{
    public function signUp(Request $request)
    {
        try {
            $user = User::create($request->all());
            $user->password = \Hash::make($request->input('password'));
            $user->save();
            $token = \JWTAuth::fromUser($user);
            return Response::json(compact('token'));
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
            ]);
        }
    }

    public function signIn(Request $request)
    {
        $credentials = $request->all();

        $token = \JWTAuth::attempt($credentials);
        if (!$token) {
            return Response::json([
                'Auth Failed'
            ]);
        }
        return Response::json(compact('token'));
    }
}
