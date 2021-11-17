<?php

namespace Uasoft\Badaso\Helpers;

use Uasoft\Badaso\Models\Permission;
use Uasoft\Badaso\Models\RolePermission;
use Uasoft\Badaso\Models\UserRole;

class AuthenticatedUser
{
    public static function getUser()
    {
        $user = auth()->user();
        if (! is_null($user)) {
            $user->roles = self::getRoles($user->id);
            $user->permissions = self::getPermissions($user->id);
        }

        return $user;
    }

    private static function getRoles($user_id)
    {
        $prefix = config('badaso.database.prefix');

        return UserRole::join($prefix.'roles', $prefix.'roles.id', '=', $prefix.'user_roles.role_id')
                ->where('user_id', $user_id)
                ->select($prefix.'roles.*')
                ->get();
    }

    private static function getPermissions($user_id)
    {
        $prefix = config('badaso.database.prefix');

        return RolePermission::join($prefix.'user_roles', $prefix.'role_permissions.role_id', '=', $prefix.'user_roles.role_id')
            ->join($prefix.'permissions', $prefix.'permissions.id', '=', $prefix.'role_permissions.permission_id')
            ->where($prefix.'user_roles.user_id', $user_id)
            ->select($prefix.'permissions.id', $prefix.'permissions.key')
            ->get();
    }

    public static function isAllowedTo($permissions_string)
    {
        if (is_null($permissions_string)) {
            return true;
        }
        $permissions = explode(',', $permissions_string);
        $user = self::getUser();
        $user_permissions = isset($user) ? $user->permissions : [];
        $user_permissions = collect($user_permissions)->pluck('key')->toArray();

        $intersect = array_intersect($permissions, $user_permissions);

        if (count($intersect) > 0) {
            return true;
        }

        return false;
    }

    public static function ignore($permissions_string = '')
    {
        $permissions = explode(',', $permissions_string);
        $public_permissions = Permission::where('is_public', 1)->orWhere('always_allow', 1)->get();
        $public_permissions = collect($public_permissions)->pluck('key')->toArray();

        $intersect = array_intersect($permissions, $public_permissions);

        if (count($intersect) > 0) {
            return true;
        }

        return false;
    }
}
