<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required'],
                'password' => ['required'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->validate([
                'token' => ['required'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required'],
                'email' => ['required'],
                'password' => ['required', 'confirmed'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => ['required'],
                'new_password' => ['required', 'confirmed'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'exists:users.email'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'new_password' => ['required', 'confirmed'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $request->validate([
                'refresh_token' => ['required'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function verify(Request $request)
    {
        try {
            $request->validate([
                'verification_code' => ['required'],
                'token' => ['required'],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
