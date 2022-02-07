<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class BadasoApiUserRoleTest extends TestCase
{
    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testAddUserRole()
    {
        $roles = Role::all();
        $role_ids = $roles->map(function ($item) {
            return $item->id;
        })->toArray();

        $name = Str::random(10);
        $create_user = [
            'name' => $name,
            'username' => $name,
            'email' => $name.'@gmail.com',
            'password' => Hash::make($name),
        ];
        $user = User::create($create_user);

        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/user-roles/add-edit'), [
            'userId' => $user->id,
            'roles' => (array) $role_ids,
        ]);
        $response->assertSuccessful();

        $user_roles = [];
        foreach ($role_ids as $key => $role_id) {
            $user_role = UserRole::where('user_id', $user->id)->where('role_id', $role_id)->first();
            $this->assertNotEmpty($user_role);

            $user_role->delete();
        }
        $user->delete();
    }

    public function testUserRoleAll()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/user-roles/all'));
        $response->assertSuccessful();

        $response_user_roles = $response->json('data.userRoles');
        foreach ($response_user_roles as $index => $response_user_role) {
            $user_role = UserRole::where('user_id', $response_user_role['userId'])->where('role_id', $response_user_role['roleId'])->first();
            $this->assertNotEmpty($user_role);
        }
    }

    public function testUserRole()
    {
        $roles = Role::all();
        $name = Str::random(10);
        $create_user = [
            'name' => $name,
            'username' => $name,
            'email' => $name.'@gmail.com',
            'password' => Hash::make($name),
        ];
        $user = User::create($create_user);

        $user_roles = [];
        foreach ($roles as $index => $role) {
            $user_roles[] = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }

        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/user-roles'), [
            'userId' => $user->id,
        ]);
        $response->assertSuccessful();

        $response_user_roles = $response->json('data.userRoles');
        foreach ($response_user_roles as $key => $response_user_role) {
            $role_id = $response_user_role['roleId'];

            $user_role = collect($user_roles)->where('role_id', $role_id)->first();
            $this->assertNotEmpty($user_role);
        }

        foreach ($user_roles as $key => $user_role) {
            $user_role->delete();
        }
        $user->delete();
    }

    public function testUserInRole()
    {
        $roles = Role::all();
        $name = Str::random(10);
        $create_user = [
            'name' => $name,
            'username' => $name,
            'email' => $name.'@gmail.com',
            'password' => Hash::make($name),
        ];
        $user = User::create($create_user);

        $user_roles = [];
        foreach ($roles as $index => $role) {
            $user_roles[] = UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }

        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/user-roles/all-role'), [
            'userId' => $user->id,
        ]);
        $response->assertSuccessful();

        $response_user_roles = $response->json('data.userRoles');

        foreach ($user_roles as $key => $user_role) {
            $user_role_id = $user_role->role_id;

            $response_user_role = collect($response_user_roles)->where('id', $user_role_id)->first();
            $this->assertNotEmpty($response_user_role);
            $this->assertTrue($response_user_role['selected'] == 1);

            $user_role->delete();
        }
        $user->delete();
    }

    public function testFinish()
    {
        CallHelperTest::clearCache();
        CallHelperTest::handleDeleteUserAdmin();
        $this->assertTrue(true);
    }
}
