<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Uasoft\Badaso\Models\PersonalAccessToken;

class BadasoAuthenticateIframe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (isset($request->token)) {
            $token = $request->token;
            $personal_access_token = PersonalAccessToken::findToken($token);

            if (!isset($personal_access_token)) {
                return redirect(config('badaso.admin_panel_route_prefix'));
            }
        }

        return $next($request);
    }
}
