<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class Api
{
    private function error($str)
    {
        $error = [
            'error' => $str,
        ];

        return \Response::json($error);
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $token = $request->get('access_token');

        if (!$token) {
            $token = $request->get('?access_token');
        }

        $error_message = '';

        if (!$token) {
            return $this->error("Access Token not found" . $error_message);
        }

        try {
            JWTAuth::setToken($token);
            $user = JWTAuth::toUser();
        } catch (\Throwable $e) {
            return $this->error("Invalid Access Token");
        }
        if (!$user) {
            return $this->error("Invalid Access Token");
        }
        return $next($request);
    }
}
