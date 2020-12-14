<?php

namespace Uasoft\Badaso\Helpers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Uasoft\Badaso\Exceptions\SingleException;

class ApiResponse
{
    private static function send($data, $http_status = 200)
    {
        $response = CaseConvert::camel($data);

        return response()->json($response, $http_status);
    }

    public static function success($value = null)
    {
        $response = [];
        $response['success'] = true;
        if (!is_null($value)) {
            if (is_array($value)) {
                $response['records'] = $value;
            } elseif (is_object($value)) {
                $response['record'] = $value;
            } else {
                $response['value'] = $value;
            }
        }

        return self::send($response);
    }

    public static function failed($error = null)
    {
        $response = [];
        $response['success'] = false;
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
            \Log::debug($error);
            $error_list = [];
            $error_list['code'][] = $error->getCode();
            $error_list['sql'][] = $error->getSql();
            $error_list['bindings'][] = $error->getBindings();

            $response['code'] = 'query_exception';
            $response['message'] = $error->getMessage();
            $response['error_list'] = $error_list;
        } elseif ($error instanceof Exception) {
            \Log::debug($error);
            $response['code'] = 'exception';
            $response['message'] = $error->getMessage();
        } else {
            \Log::debug($error);
            $response['code'] = 'unknown_exception';
            if (is_object($error) || is_array($error)) {
                $response['message'] = json_encode($error);
            } else {
                $response['message'] = $error;
            }
        }

        return self::send($response, $http_status);
    }

    public static function entity($data_type, $data = null)
    {
        $response = [];
        $response['success'] = true;
        $response['data_type'] = $data_type;
        if (!is_null($data)) {
            if (is_array($data)) {
                $response['records'] = $data;
            } elseif (is_object($data)) {
                $response['record'] = $data;
            } else {
                $response['value'] = $data;
            }
        }

        return self::send($response);
    }

    public static function unauthorized($message = 'unauthorized')
    {
        $response['success'] = false;
        $response['code'] = 'unauthorized';
        $response['message'] = $message;
        $response['error_list'] = [];

        return self::send($response, 401);
    }

    public static function forbidden()
    {
        $response['success'] = false;
        $response['code'] = 'forbidden';
        $response['message'] = '';
        $response['error_list'] = [];

        return self::send($response, 403);
    }
}
