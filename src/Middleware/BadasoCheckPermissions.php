<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;

class BadasoCheckPermissions
{
    public function handle($request, Closure $next, $permissions)
    {
        if ($permissions == null) {
            return $next($request);
        } else {
            $continue = AuthenticatedUser::isAllowedTo($permissions);

            if ($continue) {
                return $next($request);
            } else {
                return ApiResponse::forbidden();
            }
        }
    }
}
