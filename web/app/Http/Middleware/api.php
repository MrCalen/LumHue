<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class Api
{
    private function error($str) {
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
    public function handle($request, Closure $next) {
        $token = $request->get('access_token');
        if (!$token) {
            $token = $request->get('?access_token');
        }
        $error_message = env('APP_ENV', 'production') === 'local' ? 'Use this one instead: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJkYWd1ZXNfcEB5YWthLmVwaXRhLmZyIiwiaXNzIjoiaHR0cDpcL1wvY2FsZW4ubXItY2FsZW4uZXVcL2FwaVwvc2lnbmluIiwiaWF0IjoxNDU3NjE0OTQ5LCJleHAiOjE0NjAyMDY5NDksIm5iZiI6MTQ1NzYxNDk0OSwianRpIjoiNzU2MzE4MjI5YTA1MDEyYjI4NzEwN2Y5Yzc3YTFmMDUifQ.mVtm-tqU4FpMOaHeKcDukuGPN9xW03YhFVeUCjquvlc' : '';
        if (!$token) {
            return $this->error("Access Token not found" . $error_message);
        }

        JWTAuth::setToken($token);
        $user = JWTAuth::toUser();
        if (!$user) {
            return $this->error("Invalid Access Token");
        }
        return $next($request);
    }
}
