<?php

return [
    'db_name' => env('DB_DATABASE'),
    'admin_panel_route_prefix' => env('MIX_ADMIN_PANEL_ROUTE_PREFIX', 'badaso-admin'),
    'default_menu' => env('MIX_DEFAULT_MENU', 'admin'),
    'api_route_prefix' => env('MIX_API_ROUTE_PREFIX', 'badaso-api'),
    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],
    'hidden_tables' => [ // this table will not shown on CRUD generator
        'activity_log',
        'data_rows',
        'data_types',
        'migrations',
        'password_resets',
        'menus',
        'menu_items',
        'users',
        'roles',
        'permissions',
        'configurations',
        'role_permissions',
        'user_roles',
        'user_verifications',
        'email_resets',
    ],
    'watch_tables' => [
        // table names for generating CRUD_DATA seeders.
    ],
    'configuration_groups' => [
        ['value' => 'adminPanel', 'label' => 'Admin Panel'],
        ['value' => 'landingPage', 'label' => 'Landing Page'],
    ],
    'widgets' => [
        'Uasoft\\Badaso\\Widgets\\UserWidget',
        'Uasoft\\Badaso\\Widgets\\RoleWidget',
        'Uasoft\\Badaso\\Widgets\\PermissionWidget',
    ],
];
