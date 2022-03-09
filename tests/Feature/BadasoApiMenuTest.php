<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Menu;

class BadasoApiMenuTest extends TestCase
{
    public static $KEY_MENU_LAST_CREATED_ID = 'MENU_LAST_CREATED_ID';

    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testBrowseMenu()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus'));
        $response->assertSuccessful();

        $response_menus = $response->json('data.menus');
        foreach ($response_menus as $index => $response_menu) {
            $menu_id = $response_menu['id'];
            $menu = Menu::find($menu_id);

            $this->assertNotEmpty($menu);
            $this->assertTrue($response_menu['key'] == $menu['key']);
            $this->assertTrue($response_menu['displayName'] == $menu['display_name']);
            $this->assertTrue($response_menu['icon'] == $menu['icon']);
            $this->assertTrue($response_menu['order'] == $menu['order']);
            $this->assertTrue($response_menu['isExpand'] == $menu['is_expand']);
            $this->assertTrue($response_menu['isShowHeader'] == $menu['is_show_header']);
        }
    }

    public function testReadMenu()
    {
        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus/read'), [
                'menu_id' => $menu->id,
            ]);
            $response->assertSuccessful();
            $response_menu = $response->json('data.menu');

            $this->assertTrue($response_menu['key'] == $menu['key']);
            $this->assertTrue($response_menu['displayName'] == $menu['display_name']);
            $this->assertTrue($response_menu['icon'] == $menu['icon']);
            $this->assertTrue($response_menu['order'] == $menu['order']);
            $this->assertTrue($response_menu['isExpand'] == $menu['is_expand']);
            $this->assertTrue($response_menu['isShowHeader'] == $menu['is_show_header']);
        }
    }

    public function testAddMenu()
    {
        $request_data = [
            'key' => Str::uuid(),
            'displayName' => Str::random(20),
            'icon' => 'add',
        ];

        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/menus/add'), $request_data);
        $response->assertSuccessful();

        $menu_id = $response->json('data.id');
        $menu = Menu::find($menu_id);

        $this->assertNotEmpty($menu);
        $this->assertTrue($menu['key'] == $request_data['key']);
        $this->assertTrue($menu['display_name'] == $request_data['displayName']);
        $this->assertTrue($menu['icon'] == $request_data['icon']);

        CallHelperTest::setCache(self::$KEY_MENU_LAST_CREATED_ID, $menu_id);
    }

    public function testEditMenu()
    {
        $menu_id = CallHelperTest::getCache(self::$KEY_MENU_LAST_CREATED_ID);
        $menu = Menu::find($menu_id);
        $request_data = [
            'key' => $menu->key,
            'displayName' => Str::random(20),
            'icon' => 'add',
            'menu_id' => $menu_id,
        ];

        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/menus/edit'), $request_data);
        $response->assertSuccessful();

        $menu = Menu::find($menu_id);

        $this->assertNotEmpty($menu);
        $this->assertTrue($menu['key'] == $request_data['key']);
        $this->assertTrue($menu['display_name'] == $request_data['displayName']);
        $this->assertTrue($menu['icon'] == $request_data['icon']);
    }

    public function testDeleteMenu()
    {
        $menu_id = CallHelperTest::getCache(self::$KEY_MENU_LAST_CREATED_ID);

        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/menus/delete'), [
            'menu_id' => $menu_id,
        ]);
        $response->assertSuccessful();

        $menu = Menu::find($menu_id);
        $this->assertEmpty($menu);
    }

    public function testArrangeMenu()
    {
        $order = [];
        for ($i = 0; $i <= 5; $i++) {
            $request_data = [
                'key' => Str::uuid(),
                'display_name' => Str::random(20),
                'icon' => 'add',
            ];

            $order[] = Menu::create($request_data);
        }
        $order = collect($order)->map(function ($item) {
            return $item->id;
        })->toArray();
        shuffle($order);

        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/menus/menu-options'), [
            'order' => (array) $order,
        ]);

        $response->assertSuccessful();

        foreach ($order as $index => $menu_id) {
            $menu = Menu::find($menu_id);
            $this->assertTrue($menu->order == $index + 1);

            $menu->delete();
        }
    }

    public function testFinish()
    {
        CallHelperTest::clearCache();
        CallHelperTest::handleDeleteUserAdmin();
        $this->assertTrue(true);
    }
}
