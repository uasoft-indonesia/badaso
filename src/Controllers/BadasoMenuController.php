<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoMenuController extends Controller
{
    public function getMenus(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function getMenuItems(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function addMenu(Request $request)
    {
    }

    public function addMenuItem(Request $request)
    {
    }

    public function editMenu(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenuItem(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenuItemOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMenu(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMenuItem(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
