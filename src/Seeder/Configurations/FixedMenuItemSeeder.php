<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;

class FixedMenuItemSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @throws Exception
     *
     * @return void
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
                    'target' => '_self',
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
                    'target' => '_self',
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
                    'target' => '_self',
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
                    'target' => '_self',
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
                    'title' => 'CRUD Management',
                    'url' => '/crud',
                    'target' => '_self',
                    'icon_class' => 'list_alt',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 5,
                    'permissions' => 'browse_crud_data',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                5 => [
                    'menu_id' => '2',
                    'title' => 'Database Management',
                    'url' => '/database',
                    'target' => '_self',
                    'icon_class' => 'inventory',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 5,
                    'permissions' => 'browse_database',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                6 => [
                    'menu_id' => '2',
                    'title' => 'Site Management',
                    'url' => '/site',
                    'target' => '_self',
                    'icon_class' => 'desktop_mac',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 6,
                    'permissions' => 'browse_configurations',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                7 => [
                    'menu_id' => '2',
                    'title' => 'Activity Log',
                    'url' => '/activity-log',
                    'target' => '_self',
                    'icon_class' => 'announcement',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 7,
                    'permissions' => 'browse_activitylogs',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                8 => [
                    'menu_id' => '2',
                    'title' => 'Log Viewer',
                    'url' => '/log-viewer',
                    'target' => '_self',
                    'icon_class' => 'error',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 8,
                    'permissions' => 'browse_logviewer',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                9 => [
                    'menu_id' => '2',
                    'title' => 'File Manager',
                    'url' => '/file-manager',
                    'target' => '_self',
                    'icon_class' => 'perm_media',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 8,
                    'permissions' => 'browse_file_manager',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                10 => [
                    'menu_id' => '2',
                    'title' => 'API Documentation',
                    'url' => '/api-docs',
                    'target' => '_self',
                    'icon_class' => 'menu_book',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 9,
                    'permissions' => 'browse_apidocs',
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
