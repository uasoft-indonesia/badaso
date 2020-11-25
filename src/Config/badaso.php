<?php

return [
    'dashboard_route_prefix' => env('MIX_DASHBOARD_ROUTE_PREFIX', 'badaso-admin'),
    'default_menu' => env('MIX_DEFAULT_MENU', 'admin'),
    'api_route_prefix' => 'badaso-api',
    'exclude_tables_from_bread' => [
        'data_rows',
        'data_types',
        'migrations',
        'password_resets',
        'menus',
        'menu_items',
    ],
    'storage' => [
        'disk' => 'public',
    ],
];
