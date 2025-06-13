<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelper;
use Uasoft\Badaso\Models\Permission;

class BadasoApiPermissionTest extends TestCase
{
    public static $KEY_PERMISSION_LAST_CREATED_ID = 'PERMISSION_LAST_CREATED_ID';

    public function testStartInit()
    {
        // init user login
        CallHelper::handleUserAdminAuthorize($this);
    }

    public function testBrowsePermission()
    {
        $response = CallHelper::withAuthorizeBearer($this)->json('GET', CallHelper::getUrlApiV1Prefix('/permissions'));
        $response->assertSuccessful();
    }

    public function testReadPermission()
    {
        $permissions = Permission::all();
        foreach ($permissions as $key => $permission) {
            $response = CallHelper::withAuthorizeBearer($this)->json('GET', CallHelper::getUrlApiV1Prefix('/permissions/read'), [
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

        $response = CallHelper::withAuthorizeBearer($this)->json('POST', CallHelper::getUrlApiV1Prefix('/permissions/add'), $request_data);
        $response->assertSuccessful();

        $permission_id = $response->json('data.id');
        $permission = Permission::find($permission_id)->toArray();

        $this->assertNotEmpty($permission);
        foreach ($request_data as $key => $permission_data) {
            $this->assertTrue($permission[$key] == $permission_data);
        }

        CallHelper::setCache(self::$KEY_PERMISSION_LAST_CREATED_ID, $permission_id);
    }

    public function testEditPermission()
    {
        $permission_id = CallHelper::getCache(self::$KEY_PERMISSION_LAST_CREATED_ID);
        $request_data = [
            'always_allow' => true,
            'description' => Str::uuid(),
            'is_public' => true,
            'key' => Str::uuid(),
            'id' => $permission_id,
        ];

        $response = CallHelper::withAuthorizeBearer($this)->json('PUT', CallHelper::getUrlApiV1Prefix('/permissions/edit'), $request_data);
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
        $permission_id = CallHelper::getCache(self::$KEY_PERMISSION_LAST_CREATED_ID);

        $response = CallHelper::withAuthorizeBearer($this)->json('DELETE', CallHelper::getUrlApiV1Prefix('/permissions/delete'), [
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

            $response = CallHelper::withAuthorizeBearer($this)->json('POST', CallHelper::getUrlApiV1Prefix('/permissions/add'), $request_data);
            $response->assertSuccessful();

            $ids[] = $response->json('data.id');
        }

        $response = CallHelper::withAuthorizeBearer($this)->json('DELETE', CallHelper::getUrlApiV1Prefix('/permissions/delete-multiple'), [
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
