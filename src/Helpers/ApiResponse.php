<?php

namespace Uasoft\Badaso\Helpers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Uasoft\Badaso\Exceptions\SingleException;

class ApiResponse
{
    public static function success($value = null)
    {
        $response = [];
        $response['success'] = true;
        if (!is_null($value)) {
            if (is_array($value)) {
                $response['data_list'] = $value;
            } elseif (is_object($value)) {
                $response['data_detail'] = $value;
            } else {
                $response['value'] = $value;
            }
        }

        return response()->json($response);
    }

    public static function failed($error = null)
    {
        $response = [];
        $response['success'] = true;
        $response['error_list'] = [];

        $http_status = 500;

        if ($error instanceof ValidationException) {
            $response['code'] = 'validation_exception';
            $response['message'] = $error->getMessage();
            $response['error_list'] = $error->errors();
            $http_status = 400;
        } elseif ($error instanceof SingleException) {
            $response['code'] = 'validation_exception';
            $response['message'] = $error->getMessage();
            $http_status = 400;
        } elseif ($error instanceof QueryException) {
            $error_list = [];
            $error_list['code'][] = $error->getCode();
            $error_list['sql'][] = $error->getSql();
            $error_list['bindings'][] = $error->getBindings();

            $response['code'] = 'query_exception';
            $response['message'] = $error->getMessage();
            $response['error_list'] = $error_list;
        } elseif ($error instanceof Exception) {
            $response['code'] = 'exception';
            $response['message'] = $error->getMessage();
        } else {
            $response['code'] = 'unknown_exception';
            if (is_object($error) || is_array($error)) {
                $response['message'] = json_encode($error);
            } else {
                $response['message'] = $error;
            }
        }

        return response()->json($response, $http_status);
    }

    public static function entity($data_type, $data)
    {
        $response = [];
        $response['success'] = true;
        $response['data_type'] = $data_type;
        if (!is_null($data)) {
            if (is_array($data)) {
                $response['data_list'] = $data;
            } elseif (is_object($data)) {
                $response['data_detail'] = $data;
            } else {
                $response['value'] = $data;
            }
        }

        return response()->json($response);
    }
}
