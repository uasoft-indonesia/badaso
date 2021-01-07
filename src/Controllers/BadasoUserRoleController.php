<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class BadasoUserRoleController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $user_roles = UserRole::all();

            $user_roles = $this->getDataRelations($user_roles);

            $data['user_roles'] = $user_roles;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseByUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
            $user_roles = UserRole::where('user_id', $request->user_id)->get();

            $user_roles = $this->getDataRelations($user_roles);

            $data['user_roles'] = $user_roles;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseAllRole(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
            $query = '
                SELECT A.*,
                    CASE
                        WHEN B.user_id is not null then 1
                        else 0
                    END as selected
                FROM roles A
                LEFT JOIN user_roles B ON A.id = B.role_id AND B.user_id = :user_id
            ';
            $user_roles = DB::select($query, [
                'user_id' => $request->user_id,
            ]);

            $data['user_roles'] = $user_roles;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function addOrEdit(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'roles' => 'required',
            ]);
            $roles = $request->roles;

            $user = User::find($request->user_id);
            if (!is_null($user)) {
                $stored_roles = [];
                foreach ($roles as $key => $value) {
                    $role = Role::find($value);
                    if (!is_null($role)) {
                        $user_role = [
                            'user_id' => $user->id,
                            'role_id' => $role->id,
                        ];

                        $store = UserRole::firstOrCreate($user_role);
                        $stored_roles[] = $role->id;
                    }
                }

                // its run by query builder, it will not log by spatie activity log
                UserRole::where('user_id', $user->id)->whereNotIn('role_id', $stored_roles)->delete();
            }

            $data = [];

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
