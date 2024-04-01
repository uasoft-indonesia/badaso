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
                'id' => 'required|exists:Uasoft\Badaso\Models\Permission,id',
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
                    'exists:Uasoft\Badaso\Models\Permission,id',
                ],
                'key' => "required|unique:Uasoft\Badaso\Models\Permission,key,{$request->id}",
                'description' => 'nullable',
                'always_allow' => 'required',
                'is_public' => 'required',
                'table_name' => 'nullable',
                'roles_can_see_all_data' => 'nullable',
                'field_identify_related_user' => 'nullable',
            ]);

            $permission = Permission::find($request->id);
            $permission_old = $permission->toArray();
            $permission->key = $request->key;
            $permission->description = $request->description;
            $permission->always_allow = $request->always_allow;
            $permission->is_public = $request->is_public;
            $permission->roles_can_see_all_data = $request->roles_can_see_all_data;
            $permission->field_identify_related_user = $request->field_identify_related_user;
            $permission->save();

            DB::commit();
            activity('Permissions')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => [
                    'old' => $permission_old,
                    'new' => $permission,
                ]])
                ->performedOn($permission)
                ->event('edited')
                ->log('Permission '.$permission->key.' has been edited');

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
                'key' => 'required|unique:Uasoft\Badaso\Models\Permission',
                'description' => 'nullable',
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
            activity('Permissions')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $permission])
                ->performedOn($permission)
                ->event('created')
                ->log('Permission '.$permission->key.' has been created');

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
                    'exists:Uasoft\Badaso\Models\Permission',
                ],
            ]);

            $permission = Permission::find($request->id);
            $permission->delete();

            DB::commit();
            activity('Permissions')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($permission)
                ->event('deleted')
                ->log('Permission '.$permission->key.' has been deleted');

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

            $permission_keys = [];
            foreach ($id_list as $key => $id) {
                $permission = Permission::find($id);
                $permission_keys[] = $permission->key;
                $permission->delete();
            }
            $permission_keys = join(',', $permission_keys);

            DB::commit();

            activity('Permissions')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($permission)
                ->event('deleted')
                ->log('Permission '.$permission_keys.' has been deleted');

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
