<?php

return [
    'db_name' => env('DB_DATABASE'),
    'dashboard_route_prefix' => env('MIX_DASHBOARD_ROUTE_PREFIX', 'badaso-admin'),
    'default_menu' => env('MIX_DEFAULT_MENU', 'dashboard'),
    'api_route_prefix' => env('MIX_API_ROUTE_PREFIX', 'badaso-api'),
    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],
];
