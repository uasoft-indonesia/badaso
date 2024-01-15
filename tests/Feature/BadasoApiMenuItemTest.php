<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;

class BadasoApiMenuItemTest extends TestCase
{
    public static $KEY_DATA_CREATED_MENU_ITEMS = 'DATA_CREATED_MENU_ITEMS';

    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);
    }

    public function testMenuItemBrowse()
    {
        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus/item'), [
                'menuId' => $menu->id,
            ]);
            $response->assertSuccessful();

            $response_menu_items = $response->json('data.menuItems');
            foreach ($response_menu_items as $index_response_menu_item => $response_menu_item) {
                $menu_item_id = $response_menu_item['id'];

                $menu_item = MenuItem::find($menu_item_id);
                $this->assertNotEmpty($menu_item);
                $this->assertTrue($menu_item['menu_id'] == $response_menu_item['menuId']);
                $this->assertTrue($menu_item['title'] == $response_menu_item['title']);
                $this->assertTrue($menu_item['url'] == $response_menu_item['url']);
                $this->assertTrue($menu_item['target'] == $response_menu_item['target']);
                $this->assertTrue($menu_item['icon_class'] == $response_menu_item['iconClass']);
                $this->assertTrue($menu_item['color'] == $response_menu_item['color']);
                $this->assertTrue($menu_item['parent_id'] == $response_menu_item['parentId']);
                $this->assertTrue($menu_item['order'] == $response_menu_item['order']);
                $this->assertTrue($menu_item['is_expand'] == $response_menu_item['isExpand']);
                $this->assertTrue($menu_item['permissions'] == $response_menu_item['permissions']);
            }
        }
    }

    public function testMenuItemBrowseKey()
    {
        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus/item-by-key'), [
                'menu_key' => $menu->key,
            ]);
            $response->assertSuccessful();

            $response_menu_items = $response->json('data.menuItems');
            foreach ($response_menu_items as $index_response_menu_item => $response_menu_item) {
                $menu_item_id = $response_menu_item['id'];

                $menu_item = MenuItem::find($menu_item_id);
                $this->assertNotEmpty($menu_item);
                $this->assertTrue($menu_item['menu_id'] == $response_menu_item['menuId']);
                $this->assertTrue($menu_item['title'] == $response_menu_item['title']);
                $this->assertTrue($menu_item['url'] == $response_menu_item['url']);
                $this->assertTrue($menu_item['target'] == $response_menu_item['target']);
                $this->assertTrue($menu_item['icon_class'] == $response_menu_item['iconClass']);
                $this->assertTrue($menu_item['color'] == $response_menu_item['color']);
                $this->assertTrue($menu_item['parent_id'] == $response_menu_item['parentId']);
                $this->assertTrue($menu_item['order'] == $response_menu_item['order']);
                $this->assertTrue($menu_item['is_expand'] == $response_menu_item['isExpand']);
                $this->assertTrue($menu_item['permissions'] == $response_menu_item['permissions']);
            }
        }
    }

    public function testMenuItemAdd()
    {
        $menu_items = [];
        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            $request_data = [
                'menuId' => $menu->id,
                'title' => Str::random(10),
                'url' => Str::random(10),
                'target' => '_self',
                'iconClass' => 'add',
                'color' => '#FFFFFF',
                'parentId' => null,
                'order' => null,
            ];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/menus/item/add'), $request_data);
            $response->assertSuccessful();

            $response_menu_item = $response->json('data');
            $menu_item_id = $response_menu_item['id'];
            $menu_item = MenuItem::find($menu_item_id);

            $this->assertNotEmpty($menu_item);
            $this->assertTrue($menu_item['menu_id'] == $response_menu_item['menuId']);
            $this->assertTrue($menu_item['title'] == $response_menu_item['title']);
            $this->assertTrue($menu_item['url'] == $response_menu_item['url']);
            $this->assertTrue($menu_item['target'] == $response_menu_item['target']);
            $this->assertTrue($menu_item['icon_class'] == $response_menu_item['iconClass']);
            $this->assertTrue($menu_item['color'] == $response_menu_item['color']);
            $this->assertTrue($menu_item['parent_id'] == $response_menu_item['parentId']);
            $this->assertTrue($menu_item['order'] == $response_menu_item['order']);

            $menu_items[] = [
                'menu_id' => $menu->id,
                'menu_item_id' => $menu_item_id,
            ];
        }

        CallHelperTest::setCache(self::$KEY_DATA_CREATED_MENU_ITEMS, $menu_items);
    }

    public function testMenuItemEdit()
    {
        $data_created_menu_items = CallHelperTest::getCache(self::$KEY_DATA_CREATED_MENU_ITEMS);

        foreach ($data_created_menu_items as $key => ['menu_id' => $menu_id, 'menu_item_id' => $menu_item_id]) {
            $request_data = [
                'menuItemId' => $menu_item_id,
                'menuId' => $menu_id,
                'title' => Str::random(10),
                'url' => '/'.Str::random(10),
                'target' => '_self',
                'iconClass' => 'add',
                'color' => '#OOOOOO',
            ];

            $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/menus/item/edit'), $request_data);
            $response->assertSuccessful();

            $menu_item = MenuItem::find($menu_item_id)->toArray();
            $this->assertNotEmpty($menu_item);
            $this->assertTrue($menu_item['menu_id'] == $request_data['menuId']);
            $this->assertTrue($menu_item['title'] == $request_data['title']);
            $this->assertTrue($menu_item['url'] == $request_data['url']);
            $this->assertTrue($menu_item['target'] == $request_data['target']);
            $this->assertTrue($menu_item['icon_class'] == $request_data['iconClass']);
            $this->assertTrue($menu_item['color'] == $request_data['color']);
        }
    }

    public function testMenuItemRead()
    {
        $data_created_menu_items = CallHelperTest::getCache(self::$KEY_DATA_CREATED_MENU_ITEMS);

        foreach ($data_created_menu_items as $key => ['menu_id' => $menu_id, 'menu_item_id' => $menu_item_id]) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus/item/read'), [
                'menu_id' => $menu_id,
                'menu_item_id' => $menu_item_id,
            ]);
            $response->assertSuccessful();

            $response_menu_item = $response->json('data.menuItem');

            $menu_item = MenuItem::find($menu_item_id)->toArray();
            $this->assertNotEmpty($menu_item);
            $this->assertTrue($menu_item['menu_id'] == $response_menu_item['menuId']);
            $this->assertTrue($menu_item['title'] == $response_menu_item['title']);
            $this->assertTrue($menu_item['url'] == $response_menu_item['url']);
            $this->assertTrue($menu_item['target'] == $response_menu_item['target']);
            $this->assertTrue($menu_item['icon_class'] == $response_menu_item['iconClass']);
            $this->assertTrue($menu_item['color'] == $response_menu_item['color']);
        }
    }

    public function testMenuItemArrangeItems()
    {
        $data_created_menu_items = CallHelperTest::getCache(self::$KEY_DATA_CREATED_MENU_ITEMS);
        $menuId = $data_created_menu_items[0]['menu_id'];
        $menu_items = [];
        foreach ($data_created_menu_items as $key => ['menu_id' => $menu_id, 'menu_item_id' => $menu_item_id]) {
            $menu_items[] = MenuItem::find($menu_item_id)->toArray();
        }

        $menu_items = collect($menu_items)->map(function ($item) {
            return [
                'id' => $item['id'],
            ];
        })->toArray();

        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/menus/arrange-items'), [
            'menuId' => $menuId,
            'menuItems' => (array) $menu_items,
        ]);
        $response->assertSuccessful();

        foreach ($menu_items as $index => $menu_item) {
            $order = $index + 1;
            $menu_item_id = $menu_item['id'];

            $menu_item = MenuItem::find($menu_item_id);
            $this->assertNotEmpty($menu_item);
            $this->assertTrue($menu_item['order'] == $order);
        }
    }

    public function testMenuItemDelete()
    {
        $data_created_menu_items = CallHelperTest::getCache(self::$KEY_DATA_CREATED_MENU_ITEMS);
        foreach ($data_created_menu_items as $key => ['menu_id' => $menu_id, 'menu_item_id' => $menu_item_id]) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/menus/item/delete'), [
                'menuId' => $menu_id,
                'menuItemId' => $menu_item_id,
            ]);
            $response->assertSuccessful();

            $menu_item = MenuItem::find($menu_item_id);
            $this->assertEmpty($menu_item);
        }
    }

    public function testMenuItemGetPermission()
    {
        $permission_keys = Permission::get()->map(function ($item) {
            return $item->key;
        })->toArray();

        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            shuffle($permission_keys);
            $permission_menu_keys = array_slice($permission_keys, 0, rand(1, 5));
            $permissions = join(',', $permission_menu_keys);

            $menu_id = $menu->id;
            $create_data = [
                'menu_id' => $menu_id,
                'title' => Str::random(10),
                'url' => Str::random(10),
                'target' => '_self',
                'icon_class' => 'add',
                'color' => '#FFFFFF',
                'order' => 1,
                'permissions' => $permissions,
            ];
            $menu_item = MenuItem::create($create_data);

            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/menus/item/permissions'), [
                'menu_id' => $menu_id,
                'menu_item_id' => $menu_item->id,
            ]);
            $response->assertSuccessful();

            $response_menu_item_permissions = $response->json('data.menuItemPermissions');
            $response_menu_item_permissions = collect($response_menu_item_permissions)->filter(function ($item) {
                return $item['selected'];
            })->each(function ($permission) use ($permission_menu_keys) {
                $permission_key = $permission['key'];
                $this->assertTrue(in_array($permission_key, $permission_menu_keys));
            });

            $menu_item->delete();
        }
    }

    public function testMenuItemEditPermission()
    {
        $menu_permissions = Permission::get()->toArray();

        $menus = Menu::all();
        foreach ($menus as $index => $menu) {
            shuffle($menu_permissions);
            $rand_menu_permissions = array_slice($menu_permissions, 0, rand(1, 5));

            $permission_menu_ids = collect($rand_menu_permissions)->map(function ($item) {
                return $item['id'];
            })->toArray();

            $permission_menu_keys = collect($rand_menu_permissions)->map(function ($item) {
                return $item['key'];
            })->toArray();
            $permission_menu_keys = join(',', $permission_menu_keys);

            $menu_id = $menu->id;
            $create_data = [
                'menu_id' => $menu_id,
                'title' => Str::random(10),
                'url' => Str::random(10),
                'target' => '_self',
                'icon_class' => 'add',
                'color' => '#FFFFFF',
                'order' => 1,
                'permissions' => null,
            ];
            $menu_item = MenuItem::create($create_data);

            $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/menus/item/permissions'), [
                'menuId' => $menu_id,
                'menuItemId' => $menu_item->id,
                'permissions' => (array) $permission_menu_ids,
            ]);
            $response->assertSuccessful();

            $menu_item = MenuItem::find($menu_item->id);
            $this->assertNotEmpty($menu_item->permissions);
            $menu_item_permissions = explode(',', $menu_item->permissions);

            foreach ($rand_menu_permissions as $index => $rand_menu_permission) {
                $menu_item_permission_key = $rand_menu_permission['key'];

                $this->assertTrue(in_array($menu_item_permission_key, $menu_item_permissions));
            }

            $menu_item->delete();
        }
    }

    public function testFinish()
    {
        CallHelperTest::clearCache();
        CallHelperTest::handleDeleteUserAdmin();
        $this->assertTrue(true);
    }
}
