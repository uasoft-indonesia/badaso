<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Permission;

class BadasoApiPermissionTest extends TestCase
{
    public static $KEY_PERMISSION_LAST_CREATED_ID = 'PERMISSION_LAST_CREATED_ID';

    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testBrowsePermission()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/permissions'));
        $response->assertSuccessful();
    }

    public function testReadPermission()
    {
        $permissions = Permission::all();
        foreach ($permissions as $key => $permission) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/permissions/read'), [
                'id' => $permission->id,
            ]);
            $response->assertSuccessful();

            $response_data = $response->json('data.permission');

            $this->assertSame($permission->key, $response_data['key']);
            $this->assertSame($permission->table_name, $response_data['tableName']);
            $this->assertSame($permission->always_allow, $response_data['alwaysAllow']);
            $this->assertSame($permission->is_public, $response_data['isPublic']);
        }
    }

    public function testAddPermission()
    {
        $request_data = [
            'always_allow' => true,
            'description' => Str::uuid(),
            'is_public' => true,
            'key' => Str::uuid(),
        ];

        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/permissions/add'), $request_data);
        $response->assertSuccessful();

        $permission_id = $response->json('data.id');
        $permission = Permission::find($permission_id)->toArray();

        $this->assertNotEmpty($permission);
        foreach ($request_data as $key => $permission_data) {
            $this->assertTrue($permission[$key] == $permission_data);
        }

        CallHelperTest::setCache(self::$KEY_PERMISSION_LAST_CREATED_ID, $permission_id);
    }

    public function testEditPermission()
    {
        $permission_id = CallHelperTest::getCache(self::$KEY_PERMISSION_LAST_CREATED_ID);
        $request_data = [
            'always_allow' => true,
            'description' => Str::uuid(),
            'is_public' => true,
            'key' => Str::uuid(),
            'id' => $permission_id,
        ];

        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/permissions/edit'), $request_data);
        $response->assertSuccessful();

        $permission_id = $response->json('data.id');
        $permission = Permission::find($permission_id)->toArray();

        $this->assertNotEmpty($permission);
        foreach ($request_data as $key => $permission_data) {
            $this->assertTrue($permission[$key] == $permission_data);
        }
    }

    public function testDeletePermission()
    {
        $permission_id = CallHelperTest::getCache(self::$KEY_PERMISSION_LAST_CREATED_ID);

        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/permissions/delete'), [
            'id' => $permission_id,
        ]);
        $response->assertSuccessful();

        $permission = Permission::find($permission_id);
        $this->assertEmpty($permission);
    }

    public function testDeleteMultiplePermission()
    {
        $maximal_count = 10;
        $ids = [];
        for ($i = 1; $i <= $maximal_count; $i++) {
            $request_data = [
                'always_allow' => true,
                'description' => Str::uuid(),
                'is_public' => true,
                'key' => Str::uuid(),
            ];

            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/permissions/add'), $request_data);
            $response->assertSuccessful();

            $ids[] = $response->json('data.id');
        }

        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/permissions/delete-multiple'), [
            'ids' => join(',', $ids),
        ]);
        $response->assertSuccessful();

        $permissions = Permission::whereIn('id', $ids)->get();
        $permission_count = $permissions->count();
        $this->assertTrue($permission_count == 0);
    }

    public function testFinish()
    {
        $this->assertTrue(true);
    }
}
