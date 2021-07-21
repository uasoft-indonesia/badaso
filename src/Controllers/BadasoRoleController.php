<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Rules\ExistsModel;
use Uasoft\Badaso\Rules\UniqueModel;

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
                'id' => ['required', new ExistsModel(Role::class, 'id')],
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
                'id'           => ['required', new ExistsModel(Role::class, 'id')],
                'name'         => ['required', new UniqueModel(Role::class, 'name', $request->id)],
                'display_name' => 'required',
                'description'  => 'nullable',
            ]);

            $role = Role::find($request->id);
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();

            DB::commit();

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
                'name'         => ['required', new UniqueModel(Role::class, 'name')],
                'display_name' => 'required',
                'description'  => 'nullable',
            ]);

            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();

            DB::commit();

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
                    new ExistsModel(Role::class, 'id')
                ],
            ]);

            Role::find($request->id)->delete();

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
                Role::find($id)->delete();
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
