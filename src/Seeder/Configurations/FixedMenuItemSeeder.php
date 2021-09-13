<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;

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
            $menu_id = Menu::where('key', 'core')->firstOrFail()->id;
            $menu_items = [
                0 => [
                    'id' => 1,
                    'menu_id' => $menu_id,
                    'title' => 'Permission Management',
                    'url' => '/permission',
                    'target' => '_self',
                    'icon_class' => 'lock',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 1,
                    'permissions' => 'browse_permissions',
                ],
                1 => [
                    'id' => 2,
                    'menu_id' => $menu_id,
                    'title' => 'Role Management',
                    'url' => '/role',
                    'target' => '_self',
                    'icon_class' => 'accessibility',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 2,
                    'permissions' => 'browse_roles',
                ],
                2 => [
                    'id' => 3,
                    'menu_id' => $menu_id,
                    'title' => 'User Management',
                    'url' => '/user',
                    'target' => '_self',
                    'icon_class' => 'person',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 3,
                    'permissions' => 'browse_users',
                ],
                3 => [
                    'id' => 4,
                    'menu_id' => $menu_id,
                    'title' => 'Menu Management',
                    'url' => '/menu',
                    'target' => '_self',
                    'icon_class' => 'menu',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 4,
                    'permissions' => 'browse_menus',
                ],
                4 => [
                    'id' => 5,
                    'menu_id' => $menu_id,
                    'title' => 'CRUD Management',
                    'url' => '/crud',
                    'target' => '_self',
                    'icon_class' => 'list_alt',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 5,
                    'permissions' => 'browse_crud_data',
                ],
                5 => [
                    'id' => 6,
                    'menu_id' => $menu_id,
                    'title' => 'Database Management',
                    'url' => '/database',
                    'target' => '_self',
                    'icon_class' => 'inventory',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 5,
                    'permissions' => 'browse_database',
                ],
                6 => [
                    'id' => 7,
                    'menu_id' => $menu_id,
                    'title' => 'Configuration',
                    'url' => '/configuration',
                    'target' => '_self',
                    'icon_class' => 'settings',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 6,
                    'permissions' => 'browse_configurations',
                ],
                7 => [
                    'id' => 8,
                    'menu_id' => $menu_id,
                    'title' => 'Activity Log',
                    'url' => '/activity-log',
                    'target' => '_self',
                    'icon_class' => 'announcement',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 7,
                    'permissions' => 'browse_activitylogs',
                ],
                8 => [
                    'id' => 9,
                    'menu_id' => $menu_id,
                    'title' => 'Log Viewer',
                    'url' => '/log-viewer',
                    'target' => '_self',
                    'icon_class' => 'error',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 8,
                    'permissions' => 'browse_logviewer',
                ],
                9 => [
                    'id' => 10,
                    'menu_id' => $menu_id,
                    'title' => 'File Manager',
                    'url' => '/file-manager',
                    'target' => '_self',
                    'icon_class' => 'perm_media',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 8,
                    'permissions' => 'browse_file_manager',
                ],
                10 => [
                    'id' => 11,
                    'menu_id' => $menu_id,
                    'title' => 'API Documentation',
                    'url' => '/api-docs',
                    'target' => '_self',
                    'icon_class' => 'menu_book',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 9,
                    'permissions' => 'browse_apidocs',
                ],
            ];

            $new_menu_items = [];
            foreach ($menu_items as $key => $value) {
                $menu_item = MenuItem::where('menu_id', $value['menu_id'])
                    ->where('url', $value['url'])
                    ->first();

                if (isset($menu_item)) {
                    continue;
                }

                MenuItem::create($value);
            }
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }

        \DB::commit();
    }
}
