<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function SignUp(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = User::create([
                'email' => $email,
                'password' => $password,
            ]);
        } catch (\Exception $e)
        {}

        $token = JWTAuth::fromUser($user);

        return Response::json(compact('token'));
    }

    public function SignIn(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return Response::json('{false}');
        }

        return Response::json(compact('token'));
    }

}
