<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\ApiResponse;

class VerifyLicence
{
    public function handle($request, Closure $next)
    {
        $licence = env('BADASO_LICENCE_KEY');
        if (is_null($licence)) {
            return ApiResponse::paymentRequired('Invalid Badaso Licence Key');
        } else {
            // Call Badaso Dashboard API here
        }

        return $next($request);
    }
}
