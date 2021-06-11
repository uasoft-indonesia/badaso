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
            'browse_user_role',
            'add_or_edit_user_role',

            'browse_role_permission',
            'add_or_edit_role_permission',

            'browse_crud_data',
            'read_crud_data',
            'edit_crud_data',
            'add_crud_data',
            'delete_crud_data',

            'browse_activitylogs',
            'read_activitylogs',

            'browse_file_manager',
            'read_file_manager',

            'browse_logviewer',
            'rollback_database',
            'migrate_database',
            'browse_migration',
            'delete_migration',
            'browse_apidocs',
            'upload_file',
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

        Permission::generateFor('database');
    }
}
