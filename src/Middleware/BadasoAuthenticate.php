<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\TokenManagement;

class BadasoAuthenticate extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->isAuthorize($request)) {
            return $next($request);
        }

        return ApiResponse::unauthorized();
    }

    protected function isAuthorize($request)
    {
        $guard = config('badaso.authenticate.guard');

        if ($this->auth->guard($guard)->check()) {
            $this->auth->shouldUse($guard);

            if (TokenManagement::fromAuth()->hasTimeoutConnection()) {
                return false;
            }

            return true;
        }

        if (! $request->expectsJson()) {
            return false;
        }

        return false;
    }
}
