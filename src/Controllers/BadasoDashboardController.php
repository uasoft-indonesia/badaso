<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Interfaces\WidgetInterface;

class BadasoDashboardController extends Controller
{
    public function verifyLicense()
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

                return ApiResponse::success([]);
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
    }

    public function index()
    {
        try {
            $widgets = config('badaso.widgets');
            $data = [];
            foreach ($widgets as $widget) {
                $widget_class = new $widget();
                if ($widget_class instanceof WidgetInterface) {
                    $permissions = $widget_class->getPermissions();
                    if (is_null($permissions)) {
                        $widget_data = $widget_class->run();
                        $data[] = $widget_data;
                    } else {
                        $allowed = AuthenticatedUser::isAllowedTo($permissions);
                        if ($allowed) {
                            $widget_data = $widget_class->run();
                            $data[] = $widget_data;
                        }
                    }
                }
            }

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
