<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Permission;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\RolePermission;
use Uasoft\Badaso\Rules\ExistsModel;

class BadasoRolePermissionController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $role_permissions = RolePermission::all();

            $role_permissions = $this->getDataRelations($role_permissions);

            $data['role_permissions'] = $role_permissions;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseByRole(Request $request)
    {
        try {
            $request->validate([
                'role_id' => ['required', new ExistsModel(Role::class, 'id')],
            ]);
            $role_permissions = RolePermission::where('role_id', $request->role_id)->get();

            $role_permissions = $this->getDataRelations($role_permissions);

            $data['role_permissions'] = $role_permissions;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseAllPermission(Request $request)
    {
        try {
            $request->validate([
                'role_id' => ['required', new ExistsModel(Role::class, 'id')],
            ]);
            $prefix = config('badaso.database.prefix');
            $query = '
                SELECT A.*,
                    CASE
                        WHEN B.role_id is not null then 1
                        else 0
                    END as selected
                    FROM '.$prefix.'permissions A
                    LEFT JOIN '.$prefix.'role_permissions B ON A.id = B.permission_id AND B.role_id = :role_id
            ';
            $role_permissions = DB::select($query, [
                'role_id' => $request->role_id,
            ]);

            $data['role_permissions'] = $role_permissions;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function addOrEdit(Request $request)
    {
        try {
            $request->validate([
                'role_id'     => ['required', new ExistsModel(Role::class, 'id')],
                'permissions' => 'required',
            ]);
            $permissions = $request->permissions;

            $role = Role::find($request->role_id);
            if (! is_null($role)) {
                $stored_permissions = [];
                foreach ($permissions as $key => $value) {
                    $permission = Permission::find($value);
                    if (! is_null($permission)) {
                        $role_permission = [
                            'role_id'       => $role->id,
                            'permission_id' => $permission->id,
                        ];

                        $store = RolePermission::firstOrCreate($role_permission);
                        $stored_permissions[] = $permission->id;
                    }
                }

                $deleted_items = RolePermission::where('role_id', $role->id)->whereNotIn('permission_id', $stored_permissions)->get();

                foreach ($deleted_items as $item) {
                    RolePermission::find($item->id)->delete();
                }
            }

            $data = [];

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
