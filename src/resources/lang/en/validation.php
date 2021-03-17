<?php

return [
    'auth' => [
        'invalid_credentials' => 'Invalid credentials',
        'user_not_found' => 'User not found',
        'wrong_old_password' => 'Invalid old password',
        'password_not_changes' => 'new password must be different with old password',
    ],
    'crud' => [
        'table_not_found' => 'Table :table does not exists',
        'table_column_not_found' => 'Invalid rows, Field :table_column does not exists',
    ],
    'base64' => [
        'length_invalid' => 'Base64 format is invalid',
        'header_invalid' => 'Base64 header is invalid',
        'mimetype_invalid' => 'Base64 mimetype is invalid',
    ],
    'database' => [
        'table_already_exists' => 'Table :table already exists.',
        'table_name_already_exists' => 'Table name of :table already exists.',
        'migration_created' => 'Migration successfully created and migrated.',
        'table_not_found' => 'Table :table does not exists',
        'nothing_changed' => 'Request was successful, but nothing changed.'
    ]
];
