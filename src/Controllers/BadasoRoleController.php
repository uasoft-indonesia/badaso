<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Role;

class BadasoRoleController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $roles = Role::all();

            $data['roles'] = $roles;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Models\Role,id',
            ]);

            $role = Role::find($request->id);

            $data['role'] = $role;

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
                    'exists:Uasoft\Badaso\Models\Role,id',
                ],
                'name' => "required|unique:Uasoft\Badaso\Models\Role,name,{$request->id}",
                'display_name' => 'required',
                'description' => 'nullable',
            ]);

            $role = Role::find($request->id);
            $old_role = $role;
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();

            DB::commit();
            activity('Role Controllers')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => [
                    'old' => $old_role,
                    'new' => $role,
                ]])
                ->performedOn($role)
                ->event('updated')
                ->log('Role '.$role->name.' has been updated');

            return ApiResponse::success($role);
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
                'name' => 'required|unique:Uasoft\Badaso\Models\Role,name',
                'display_name' => 'required',
                'description' => 'nullable',
            ]);

            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();

            DB::commit();
            activity('Role Controllers')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $role])
                ->performedOn($role)
                ->event('created')
                ->log('Role '.$role->name.' has been created');

            return ApiResponse::success($role);
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
                    'exists:Uasoft\Badaso\Models\Role',
                ],
            ]);

            $role = Role::find($request->id);
            $role->delete();

            DB::commit();
            activity('Role Controllers')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($role)
                ->event('deleted')
                ->log('Role '.$role->name.' has been deleted');

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
            $role_name = [];
            foreach ($id_list as $key => $id) {
                $role = Role::find($id);
                $role_name[] = $role->name;
                $role->delete();
            }
            $role_name = join(',', $role_name);

            DB::commit();

            activity('Role Controllers')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($role)
                ->event('deleted')
                ->log('Role '.$role->name.' has been deleted');

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
