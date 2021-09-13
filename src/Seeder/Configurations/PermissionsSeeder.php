<?php

namespace Database\Seeders\Badaso;

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
            ['key' => 'browse_user_role', 'always_allow' => 0],
            ['key' => 'add_or_edit_user_role', 'always_allow' => 0],

            ['key' => 'browse_role_permission', 'always_allow' => 0],
            ['key' => 'add_or_edit_role_permission', 'always_allow' => 0],

            ['key' => 'browse_crud_data', 'always_allow' => 0],
            ['key' => 'read_crud_data', 'always_allow' => 1],
            ['key' => 'edit_crud_data', 'always_allow' => 0],
            ['key' => 'add_crud_data', 'always_allow' => 0],
            ['key' => 'delete_crud_data', 'always_allow' => 0],

            ['key' => 'browse_activitylogs', 'always_allow' => 0],
            ['key' => 'read_activitylogs', 'always_allow' => 0],

            ['key' => 'browse_file_manager', 'always_allow' => 0],
            ['key' => 'read_file_manager', 'always_allow' => 0],

            ['key' => 'browse_logviewer', 'always_allow' => 0],
            ['key' => 'rollback_database', 'always_allow' => 0],
            ['key' => 'migrate_database', 'always_allow' => 0],
            ['key' => 'browse_migration', 'always_allow' => 0],
            ['key' => 'delete_migration', 'always_allow' => 0],
            ['key' => 'browse_apidocs', 'always_allow' => 0],
            ['key' => 'upload_file', 'always_allow' => 0],
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key' => $key['key'],
                'table_name' => null,
                'always_allow' => $key['always_allow'],
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('menu_items');

        Permission::generateFor('permissions');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('configurations');

        Permission::generateFor('database');
    }
}
