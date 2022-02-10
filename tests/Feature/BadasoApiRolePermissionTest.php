<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Permission;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\RolePermission;

class BadasoApiRolePermissionTest extends TestCase
{
    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testRoleInPermissions()
    {
        $roles = Role::all();
        foreach ($roles as $index => $role) {
            $role_id = $role->id;
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/role-permissions/all-permission'), [
                'roleId' => $role_id,
            ]);
            $response->assertSuccessful();

            $response_data = $response->json('data.rolePermissions');
            foreach ($response_data as $index => $response_permission) {
                $permission_id = $response_permission['id'];
                $permission = Permission::find($permission_id)->toArray();

                $this->assertNotEmpty($permission);
                $this->assertTrue($permission['key'] == $response_permission['key']);
                $this->assertTrue($permission['description'] == $response_permission['description']);
                $this->assertTrue($permission['table_name'] == $response_permission['tableName']);
                $this->assertTrue($permission['always_allow'] == $response_permission['alwaysAllow']);
                $this->assertTrue($permission['is_public'] == $response_permission['isPublic']);
            }
        }
    }

    public function testRolePermissionAll()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/role-permissions/all'));
        $response->assertSuccessful();

        $response_data_permissions = $response->json('data.rolePermissions');
        foreach ($response_data_permissions as $index => $response_data_permission) {
            $permission_id = $response_data_permission['id'];
            $role_permission = RolePermission::find($permission_id)->toArray();

            $this->assertTrue($role_permission['role_id'] == $response_data_permission['roleId']);
            $this->assertTrue($role_permission['permission_id'] == $response_data_permission['permissionId']);
        }
    }

    public function testRolePermission()
    {
        $roles = Role::all();
        foreach ($roles as $index => $role) {
            $role_id = $role['id'];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/role-permissions'), [
                'roleId' => $role_id,
            ]);
            $response->assertSuccessful();
            $response_role_permissions = $response->json('data.rolePermissions');

            foreach ($response_role_permissions as $index => $response_role_permission) {
                $role_permission_id = $response_role_permission['id'];
                $role_permission = RolePermission::find($role_permission_id);

                $this->assertNotEmpty($role_permission);
                $this->assertTrue($role_permission['role_id'] == $response_role_permission['roleId']);
                $this->assertTrue($role_permission['permission_id'] == $response_role_permission['permissionId']);
            }
        }
    }

    public function testAddRolePermissionUser()
    {
        $role = Role::create([
            'name' => Str::random(10),
            'display_name' => Str::random(10),
            'description' => Str::random(10),
        ]);

        $role_id = $role->id;
        $default_permissions = RolePermission::get()->map(function ($item) {
            return $item['permission_id'];
        })->values()->toArray();

        $request_permissions = $default_permissions;
        shuffle($request_permissions);
        $request_permissions = array_slice($request_permissions, 0, rand(1, count($request_permissions)));

        $request_data = [
            'roleId' => $role_id,
            'permissions' => $request_permissions,
        ];
        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/role-permissions/add-edit'), $request_data);
        $response->assertSuccessful();

        foreach ($request_permissions as $index => $request_permission) {
            $role_permission = RolePermission::where('role_id', $role_id)->where('permission_id', $request_permission)->first();
            $this->assertNotEmpty($role_permission);
        }

        foreach ($default_permissions as $key => $default_permission) {
            $role_permission = RolePermission::where('role_id', $role_id)->where('permission_id', $default_permission)->first();
            if (isset($role_permission)) {
                $role_permission->update([
                    'role_id' => $role_id,
                    'permission_id' => $default_permission,
                ]);
            } else {
                $role_permission = RolePermission::create([
                    'role_id' => $role_id,
                    'permission_id' => $default_permission,
                ]);
            }
        }

        $role->delete();
    }

    public function testFinish()
    {
        CallHelperTest::clearCache();
        CallHelperTest::handleDeleteUserAdmin();
        $this->assertTrue(true);
    }
}
