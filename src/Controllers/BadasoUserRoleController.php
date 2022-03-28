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

            foreach ($user_roles as $index => $user_role) {
                $user_role->role;
                $user_role->user;
            }

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
                'user_id' => 'required|exists:Uasoft\Badaso\Models\User,id',
            ]);
            $user_roles = UserRole::where('user_id', $request->user_id)->get();

            foreach ($user_roles as $index => $user_role) {
                $user_role->role;
                $user_role->user;
            }

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
                'user_id' => 'required|exists:Uasoft\Badaso\Models\User,id',
            ]);
            $prefix = config('badaso.database.prefix');
            $query = '
                SELECT A.*,
                    CASE
                        WHEN B.user_id is not null then 1
                        else 0
                    END as selected
                FROM '.$prefix.'roles A
                LEFT JOIN '.$prefix.'user_roles B ON A.id = B.role_id AND B.user_id = :user_id
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
                'user_id' => 'required|exists:Uasoft\Badaso\Models\User,id',
            ]);
            $roles = $request->get('roles', []);

            $user = User::find($request->user_id);
            if (! is_null($user)) {
                $stored_roles = [];
                foreach ($roles as $key => $value) {
                    $role = Role::find($value);
                    if (! is_null($role)) {
                        $user_role = [
                            'user_id' => $user->id,
                            'role_id' => $role->id,
                        ];

                        $store = UserRole::firstOrCreate($user_role);
                        $stored_roles[] = $role->id;
                    }
                }

                $deleted_items = UserRole::where('user_id', $user->id)->whereNotIn('role_id', $stored_roles)->get();
                foreach ($deleted_items as $item) {
                    UserRole::find($item->id)->delete();
                }
            }

            $data = [];
            activity('User')
            ->causedBy(auth()->user() ?? null)
                ->performedOn($user)
                ->event('created or updated')
                ->log('User '.$user->name.' has been created or updated');

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
