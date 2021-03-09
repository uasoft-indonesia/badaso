<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;

class BadasoCheckPermissions
{
    public function handle($request, Closure $next, $permissions)
    {
        if ($permissions == null) {
            return $next($request);
        } else {
            $continue = AuthenticatedUser::ignore($permissions);
            if ($continue) {
                return $next($request);
            } else {
                if (Auth::check()) {
                    $continue = AuthenticatedUser::isAllowedTo($permissions);
                    if ($continue) {
                        return $next($request);
                    } else {
                        return ApiResponse::forbidden();
                    }
                }

                return ApiResponse::unauthorized();
            }
        }
    }
}
