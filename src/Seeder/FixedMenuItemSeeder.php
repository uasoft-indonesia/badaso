<?php

use Illuminate\Database\Seeder;

class FixedMenuItemSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        \DB::beginTransaction();
        try {
            $menu_items = [
                0 => [
                    'menu_id' => '2',
                    'title' => 'Permission Management',
                    'url' => '/permission',
                    'target' => '',
                    'icon_class' => 'lock',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 1,
                    'permissions' => 'browse_permissions',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                1 => [
                    'menu_id' => '2',
                    'title' => 'Role Management',
                    'url' => '/role',
                    'target' => '',
                    'icon_class' => 'accessibility',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 2,
                    'permissions' => 'browse_roles',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                2 => [
                    'menu_id' => '2',
                    'title' => 'User Management',
                    'url' => '/user',
                    'target' => '',
                    'icon_class' => 'person',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 3,
                    'permissions' => 'browse_users',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                3 => [
                    'menu_id' => '2',
                    'title' => 'Menu Management',
                    'url' => '/menu',
                    'target' => '',
                    'icon_class' => 'menu',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 4,
                    'permissions' => 'browse_menus',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                4 => [
                    'menu_id' => '2',
                    'title' => 'BREAD Management',
                    'url' => '/bread',
                    'target' => '',
                    'icon_class' => 'list_alt',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 5,
                    'permissions' => 'browse_bread',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                5 => [
                    'menu_id' => '2',
                    'title' => 'Site Management',
                    'url' => '/site',
                    'target' => '',
                    'icon_class' => 'desktop_mac',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 6,
                    'permissions' => 'browse_configurations',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
            ];

            $new_menu_items = [];
            foreach ($menu_items as $key => $value) {
                $menu_item = \DB::table('menu_items')
                        ->where('menu_id', $value['menu_id'])
                        ->where('url', $value['url'])
                        ->first();

                if (isset($menu_item)) {
                    continue;
                }
                $new_menu_items[] = $value;
            }

            \DB::table('menu_items')->insert($new_menu_items);
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }

        \DB::commit();
    }
}
