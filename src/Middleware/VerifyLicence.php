<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Uasoft\Badaso\Helpers\ApiResponse;

class VerifyLicence
{
    public function handle($request, Closure $next)
    {
        // $licence = env('BADASO_LICENCE_KEY');
        // if (is_null($licence)) {
        //     return ApiResponse::paymentRequired('BADASO_LICENCE_KEY not found');
        // } else {
        //     // Call Badaso Dashboard API here
        //     try {
        //         $client = new Client();
        //         $req = $client->request('POST', 'http://dev.badaso-dashboard.com/api/verify-licence', [
        //             'json' => [
        //                 'licence' => $licence,
        //             ],
        //         ]);
        //     } catch (BadResponseException $e) {
        //         if ($e->hasResponse()) {
        //             $response = $e->getResponse()->getBody();
        //             $status = $e->getResponse()->getStatusCode();

        //             return response(json_decode($response, true), $status);
        //         } else {
        //             return ApiResponse::failed($e);
        //         }
        //     } catch (Exception $e) {
        //         return ApiResponse::failed($e);
        //     }
        // }

        return $next($request);
    }
}
