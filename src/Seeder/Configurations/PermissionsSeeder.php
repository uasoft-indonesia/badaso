<?php

use Illuminate\Database\Seeder;
use Uasoft\Badaso\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_user_role',
            'add_or_edit_user_role',

            'browse_role_permission',
            'add_or_edit_role_permission',

            'browse_bread',
            'read_bread',
            'edit_bread',
            'add_bread',
            'delete_bread',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key' => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('menu_items');

        Permission::generateFor('permissions');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('configurations');
    }
}
