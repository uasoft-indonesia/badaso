<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Role;

class BadasoApiRoleTest extends TestCase
{
    public static $KEY_ROLE_LAST_CREATE_ID = 'ROLE_LAST_CREATE_ID';

    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testAddRole()
    {
        $request_data = [
            'name' => Str::uuid(),
            'display_name' => 'Example Display Name',
            'description' => 'Example Description',
        ];
        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/roles/add'), $request_data);
        $response->assertSuccessful();

        $role_id = $response->json('data.id');
        $role = Role::find($role_id);

        $this->assertNotEmpty($role);
        $this->assertTrue($role['name'] == $request_data['name']);
        $this->assertTrue($role['display_name'] == $request_data['display_name']);
        $this->assertTrue($role['description'] == $request_data['description']);

        CallHelperTest::setCache(self::$KEY_ROLE_LAST_CREATE_ID, $role_id);
    }

    public function testReadRole()
    {
        $role_id = CallHelperTest::getCache(self::$KEY_ROLE_LAST_CREATE_ID);
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/roles/read'), [
            'id' => $role_id,
        ]);
        $response->assertSuccessful();

        $response_data = $response->json('data.role');

        $role = Role::find($role_id)->toArray();

        $this->assertNotEmpty($role);
        $this->assertTrue($role['name'] == $response_data['name']);
        $this->assertTrue($role['display_name'] == $response_data['displayName']);
        $this->assertTrue($role['description'] == $response_data['description']);
    }

    public function testEditRole()
    {
        $role_id = CallHelperTest::getCache(self::$KEY_ROLE_LAST_CREATE_ID);
        $request_data = [
            'id' => $role_id,
            'name' => Str::uuid(),
            'display_name' => 'Example Display Name',
            'description' => 'Example Description',
        ];

        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/roles/edit'), $request_data);
        $response->assertSuccessful();

        $response_data = $response->json('data');

        $role = Role::find($role_id)->toArray();

        $this->assertTrue($role['name'] == $response_data['name']);
        $this->assertTrue($role['display_name'] == $response_data['displayName']);
        $this->assertTrue($role['description'] == $response_data['description']);
    }

    public function testBrowseRole()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/roles'));
        $response->assertSuccessful();

        $response_data = $response->json('data.roles');

        $roles = Role::all();
        foreach ($roles as $index => $role) {
            $this->assertSame($response_data[$index]['id'], $role['id']);
            $this->assertSame($response_data[$index]['name'], $role['name']);
            $this->assertSame($response_data[$index]['displayName'], $role['display_name']);
            $this->assertSame($response_data[$index]['description'], $role['description']);
        }
    }

    public function testDeleteRole()
    {
        $request_data = [
            'name' => Str::uuid(),
            'display_name' => 'Example Display Name',
            'description' => 'Example Description',
        ];
        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/roles/add'), $request_data);
        $response->assertSuccessful();

        $role_id = $response->json('data.id');
        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/roles/delete'), [
            'id' => $role_id,
        ]);
        $response->assertSuccessful();

        $role = Role::find($role_id);
        $this->assertEmpty($role);
    }

    public function testDeleteMultipleRole()
    {
        $role_id = CallHelperTest::getCache(self::$KEY_ROLE_LAST_CREATE_ID);
        $maximal_count = 10;
        $ids = [$role_id];
        for ($i = 1; $i <= $maximal_count; $i++) {
            $request_data = [
                'name' => Str::uuid(),
                'display_name' => "Example Display Name $i",
                'description' => 'Example Description',
            ];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/roles/add'), $request_data);
            $response->assertSuccessful();

            $role_id = $response->json('data.id');
            $ids[] = $role_id;
        }

        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/roles/delete-multiple'), [
            'ids' => join(',', $ids),
        ]);
        $response->assertSuccessful();

        $roles = Role::whereIn('id', $ids)->get();
        $this->assertTrue($roles->count() == 0);
    }

    public function testFinish()
    {
        CallHelperTest::clearCache();
        CallHelperTest::handleDeleteUserAdmin();
        $this->assertTrue(true);
    }
}
