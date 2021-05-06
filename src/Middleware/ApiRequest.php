<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\CaseConvert;
use Uasoft\Badaso\Models\Configuration;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Helpers\ApiResponse;

class ApiRequest
{
    public function handle($request, Closure $next)
    {
        $user = AuthenticatedUser::getUser();
        $prefix = config('badaso.api_route_prefix');
        $path = $request->path();

        // Let administrator & file api pass the request.
        if ($user && $user->roles[0]->name === "administrator" || strpos($path, $prefix . '/v1/file') !== false) {
            $lang = ($request->hasHeader('Accept-Language')) ? $request->header('Accept-Language') : 'en';

            app()->setLocale($lang);

            $request->merge(CaseConvert::snake($request->all()));

            return $next($request);
        } else { // Except administrator & file api, block the request.
            $whitelist = config('badaso.api_whitelist_route');
            
            // Whitelist some api in order to run the app.
            if (in_array($path, $whitelist)) { 
                return $next($request);
            } else {
                $maintenance = Configuration::where('key', 'maintenance')->firstOrFail();

                // Check is system under maintenance.
                if ($maintenance->value === "1") {
                    return ApiResponse::serviceUnavailable();
                }
    
                return $next($request);
            }
        }
    }
}
