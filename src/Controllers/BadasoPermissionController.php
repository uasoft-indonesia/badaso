<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Permission;

class BadasoPermissionController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $permissions = Permission::all();

            $data['permissions'] = $permissions;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:permissions,id',
            ]);

            $permission = Permission::find($request->id);

            $data['permission'] = $permission;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => [
                    'required',
                    'exists:permissions,id',
                ],
                'key' => "required|unique:permissions,key,{$request->id}",
                'description' => 'required',
                'always_allow' => 'required',
                'is_public' => 'required',
            ]);

            $permission = Permission::find($request->id);
            $permission->key = $request->key;
            $permission->description = $request->description;
            $permission->always_allow = $request->always_allow;
            $permission->is_public = $request->is_public;
            $permission->save();

            DB::commit();

            return ApiResponse::success($permission);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'key' => 'required|unique:permissions',
                'description' => 'required',
                'always_allow' => 'required',
                'is_public' => 'required',
            ]);

            $permission = new Permission();
            $permission->key = $request->key;
            $permission->description = $request->description;
            $permission->always_allow = $request->always_allow;
            $permission->is_public = $request->is_public;
            $permission->save();

            DB::commit();

            return ApiResponse::success($permission);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => [
                    'required',
                    'exists:permissions',
                ],
            ]);

            Permission::find($request->id)->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'ids' => [
                    'required',
                ],
            ]);

            $id_list = explode(',', $request->ids);

            foreach ($id_list as $key => $id) {
                Permission::find($id)->delete();
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
