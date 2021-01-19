<?php

return [
    'connections' => [
        'mysql' => [
            // Add dump configuration for spatie/laravel-backup > https://docs.spatie.be/laravel-backup/v5/installation-and-setup/#dumping-the-database
            'dump' => [
                'dump_binary_path' => env('DUMP_BINARY_PATH', 'C:\xampp\mysql\bin'), // only the path, so without `mysqldump` or `pg_dump`
                'use_single_transaction',
                'timeout' => 60 * 5, // 5 minute timeout
                // 'exclude_tables' => [],
                // 'add_extra_option' => ''
            ],
        ],
    ],
];
