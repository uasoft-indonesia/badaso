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
            $menu_core = Menu::where('key', 'core')->firstOrFail()->id;
            $menu_general = Menu::where('key', 'general')->firstOrFail()->id;
            $menu_items = [
                // core menu
                0 => [
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
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
                    'menu_id' => $menu_core,
                    'title' => 'API Documentation',
                    'url' => '/api-docs',
                    'target' => '_self',
                    'icon_class' => 'menu_book',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 9,
                    'permissions' => 'browse_apidocs',
                ],

                // media manager
                10 => [
                    'menu_id' => $menu_core,
                    'title' => 'Media Manager',
                    'url' => '/#',
                    'target' => '_self',
                    'icon_class' => 'folder_open',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 10,
                    'is_expand' => 1,
                    'permissions' => null,
                ],
                11 => [
                    'menu_id' => $menu_core,
                    'title' => 'Files',
                    'url' => '/file-manager',
                    'target' => '_self',
                    'icon_class' => 'folder',
                    'color' => '',
                    'parent_id' => 11,
                    'order' => 1,
                    'permissions' => 'browse_file_manager',
                ],
                12 => [
                    'menu_id' => $menu_core,
                    'title' => 'Images',
                    'url' => '/image-manager',
                    'target' => '_self',
                    'icon_class' => 'perm_media',
                    'color' => '',
                    'parent_id' => 11,
                    'order' => 2,
                    'permissions' => 'browse_file_manager',
                ],

                // general menu
                13 => [
                    'menu_id' => $menu_general,
                    'title' => 'Dashboard',
                    'url' => '/home',
                    'target' => '_self',
                    'icon_class' => 'dashboard',
                    'color' => '',
                    'parent_id' => null,
                    'order' => 1,
                    'permissions' => null,
                ],
            ];

            $new_menu_items = [];
            foreach ($menu_items as $key => $value) {
                $menu_item = MenuItem::where('menu_id', $value['menu_id'])
                    ->where('url', $value['url'])
                    ->first();

                if (isset($menu_item)) {
                    $menu_item->update($value);
                } else {
                    $menu_item = MenuItem::create($value);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('Exception occur '.$e);
            \DB::rollBack();
        }

        \DB::commit();
    }
}
