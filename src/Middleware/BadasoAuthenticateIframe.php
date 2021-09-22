<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Models\PersonalAccessToken;

class BadasoAuthenticateIframe extends BadasoAuthenticate
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
    public function handle($request, Closure $next, ...$types)
    {
        $token = $request->header('authorization');
        if ($token != null) {
            [$bearer, $token_bearer] = explode(' ', $token);
            $token = trim($token_bearer);
        } else {
            if (isset($request->token)) {
                $token = urldecode($request->token);
            } else {
                return abort(401);
            }
        }

        $personal_access_token = PersonalAccessToken::findToken($token);

        if (! isset($personal_access_token)) {
            return abort(401);
        }

        foreach ($types as $key => $type) {
            switch ($type) {
                case 'lfm':
                    break;

                case 'log-viewer':
                    break;

                case 'l5-swagger':
                    break;

                default:

                    break;
            }
        }

        return $next($request);
    }
}
