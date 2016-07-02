<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use JWTAuth;
use Request;

class Api
{
    private function error($str, $error_code = 403)
    {
        $error = [
            'error' => $str,
            'success' => false,
        ];
        return Response::json($error, $error_code);
    }

    /**
    * Handle an incoming request.
    *
    * @param   $request
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
            return $this->error("Invalid Access Token", 500);
        }
        if (!$user) {
            return $this->error("Invalid Access Token", 500);
        }

        $request->user = $user;
        return $next($request);
    }
}
