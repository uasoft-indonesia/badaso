<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\CaseConvert;

class ApiRequest
{
    public function handle($request, Closure $next)
    {
        $request->merge(CaseConvert::snake($request->all()));

        return $next($request);
    }
}
