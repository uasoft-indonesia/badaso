<?php

namespace Uasoft\Badaso\Middleware;

use Closure;

class VerifyLicense
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
