<?php

return [
    // badaso default table
    env('BADASO_TABLE_PREFIX', 'badaso_').'data_rows',
    env('BADASO_TABLE_PREFIX', 'badaso_').'data_types',
    env('BADASO_TABLE_PREFIX', 'badaso_').'menus',
    env('BADASO_TABLE_PREFIX', 'badaso_').'menu_items',
    env('BADASO_TABLE_PREFIX', 'badaso_').'users',
    env('BADASO_TABLE_PREFIX', 'badaso_').'roles',
    env('BADASO_TABLE_PREFIX', 'badaso_').'permissions',
    env('BADASO_TABLE_PREFIX', 'badaso_').'configurations',
    env('BADASO_TABLE_PREFIX', 'badaso_').'role_permissions',
    env('BADASO_TABLE_PREFIX', 'badaso_').'user_roles',
    env('BADASO_TABLE_PREFIX', 'badaso_').'user_verifications',
    env('BADASO_TABLE_PREFIX', 'badaso_').'email_resets',
    env('BADASO_TABLE_PREFIX', 'badaso_').'notifications',
    env('BADASO_TABLE_PREFIX', 'badaso_').'firebase_cloud_messages',
    env('BADASO_TABLE_PREFIX', 'badaso_').'password_resets',
    env('BADASO_TABLE_PREFIX', 'badaso_').'personal_access_tokens',

    // laravel default table
    'migrations',
    'activity_log',
    'failed_jobs',
    'personal_access_tokens',
    'users',
    'password_resets',
];
