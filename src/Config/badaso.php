<?php

return [
    'db_name' => env('DB_DATABASE'),
    'admin_panel_route_prefix' => env('MIX_ADMIN_PANEL_ROUTE_PREFIX', 'badaso-admin'),
    'default_menu' => env('MIX_DEFAULT_MENU', 'admin'),
    'api_route_prefix' => env('MIX_API_ROUTE_PREFIX', 'badaso-api'),
    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],
    'watch_tables' => [
        // table names for generating bread seeders.
    ],
];
