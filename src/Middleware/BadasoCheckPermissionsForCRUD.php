<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;

class BadasoCheckPermissionsForCRUD extends BadasoAuthenticate
{
    public function handle($request, Closure $next, ...$parameter)
    {
        [$slug, $action] = $parameter;

        $data_type = Badaso::model('DataType')::where('slug', $slug)->first();

        if ($data_type) {
            if ($data_type->generate_permissions == false) {
                return $next($request);
            } else {
                $continue = AuthenticatedUser::ignore($action.'_'.$data_type->name);
                if ($continue) {
                    return $next($request);
                } else {
                    if ($this->isAuthorize($request)) {
                        $continue = AuthenticatedUser::isAllowedTo($action.'_'.$data_type->name);
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

        return ApiResponse::forbidden();
    }
}
