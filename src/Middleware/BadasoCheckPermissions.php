<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;

class BadasoCheckPermissions extends BadasoAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        [$permissions] = $guards;

        if ($permissions == null) {
            return $next($request);
        } else {
            $continue = AuthenticatedUser::ignore($permissions);
            if ($continue) {
                return $next($request);
            } else {
                if ($this->isAuthorize($request)) {
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
