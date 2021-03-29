<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;

class VerifyLicense
{
    public function handle($request, Closure $next)
    {
        $license = env('BADASO_LICENSE_KEY');
        if (is_null($license)) {
            return ApiResponse::paymentRequired('BADASO_LICENSE_KEY not found');
        } else {
            // Call Badaso Dashboard API here
            try {
                $client = new Client();
                $req = $client->request('POST', Badaso::getBadasoVerifyApi(), [
                    'json' => [
                        'license' => $license,
                    ],
                ]);
            } catch (BadResponseException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse()->getBody();
                    $status = $e->getResponse()->getStatusCode();

                    return response(json_decode($response, true), $status);
                } else {
                    return ApiResponse::failed($e);
                }
            } catch (Exception $e) {
                return ApiResponse::failed($e);
            }
        }

        return $next($request);
    }
}
