<?php

namespace Uasoft\Badaso\Helpers;

use Uasoft\Badaso\Models\RolePermission;
use Uasoft\Badaso\Models\UserRole;

class AuthenticatedUser
{
    public static function getUser()
    {
        $user = auth()->user();
        if (!is_null($user)) {
            $user->roles = self::getRoles($user->id);
            $user->permissions = self::getPermissions($user->id);
        }

        return $user;
    }

    private static function getRoles($user_id)
    {
        return UserRole::join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->where('user_id', $user_id)
                ->select('roles.*')
                ->get();
    }

    private static function getPermissions($user_id)
    {
        return RolePermission::join('user_roles', 'role_permissions.role_id', '=', 'user_roles.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('user_roles.user_id', $user_id)
            ->select('permissions.*')
            ->get();
    }
}
