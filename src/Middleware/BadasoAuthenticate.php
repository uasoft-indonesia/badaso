<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // $token_payload = Auth::payload();
            // $expires = $token_payload['exp'];
            // $now = time();
            // if ($expires <= $now) {
            //     return ApiResponse::unauthorized('expired authorization');
            // }

            return $next($request);
        }

        return ApiResponse::unauthorized();
    }
}
