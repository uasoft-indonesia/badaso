<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Uasoft\Badaso\Controllers\Controller;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\FirebaseServices;

class BadasoFirebaseConfigController extends Controller
{
    public function GetConfig()
    {
        try {
            $firebaseService = FirebaseServices::first();
            $firebaseServiceConfig = [
                'api_key' => env('MIX_FIREBASE_API_KEY'),
                'auth_domain' => env('MIX_FIREBASE_AUTH_DOMAIN'),
                'project_id' => env('MIX_FIREBASE_PROJECT_ID'),
                'storage_bucket' => env('MIX_FIREBASE_STORAGE_BUCKET'),
                'messaging_sender_id' => env('MIX_FIREBASE_MESSAGE_SEENDER'),
                'app_id' => env('MIX_FIREBASE_APP_ID'),
                'measure_id' => env('MIX_FIREBASE_MEASUREMENT_ID'),
                'server_key' => env('MIX_FIREBASE_SERVER_KEY'),
            ];

            if (!isset($firebaseService)) $firebaseService = FirebaseServices::create([]);

            foreach (json_decode(json_encode($firebaseService)) as $key => $value) {
                if (array_key_exists($key, $firebaseServiceConfig)) {
                    $env_config = $firebaseServiceConfig[$key];
                    $now_use_config_env = trim($env_config) != null && $env_config != '';
                    if ($now_use_config_env) {
                        $firebaseService[$key] = $env_config;
                        $firebaseService['status_' . $key] = $now_use_config_env;
                    } else {
                        $firebaseService[$key] = $value;
                        $firebaseService['status_' . $key] = $now_use_config_env;
                    }
                }
            }

            return ApiResponse::success([
                'firebase_service_config' => $firebaseService,
            ]);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
    public function UpdateConfig(Request $request)
    {
        try {

            $dataConfig = [
                'api_key' => $request->api_key,
                'auth_domain' => $request->auth_domain,
                'project_id' => $request->project_id,
                'storage_bucket' => $request->storage_bucket,
                'messaging_sender_id' => $request->messaging_sender_id,
                'app_id' => $request->app_id,
                'measurement_id' => $request->measurement_id,
                'server_key' => $request->server_key,
            ];

            $firebaseService = FirebaseServices::first();

            if (!isset($firebaseService)) $firebaseService = FirebaseServices::create($dataConfig);
            else $firebaseService->update($dataConfig);

            return ApiResponse::success([
                'firebase_service_config' => $firebaseService,
            ]);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
