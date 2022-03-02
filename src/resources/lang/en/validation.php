<?php

return [
    'auth' => [
        'invalid_credentials'  => 'Invalid email or password',
        'user_not_found'       => 'User not found',
        'wrong_old_password'   => 'Invalid old password',
        'password_not_changes' => 'new password must be different with old password',
    ],
    'crud' => [
        'table_not_found'                     => 'Table :table does not exists',
        'table_column_not_found'              => 'Invalid rows, Field :table_column does not exists',
        'table_column_not_have_default_value' => 'Invalid rows, Field :table_column has no default value, please tick the checkbox Add',
        'table_deleted_at_not_exists'         => 'Invalidate columns deleted_at, please created new columns delete_at in your table :table_name',
        'id_table_wrong'                      => 'Primary key should be named only"id"',
    ],
    'base64' => [
        'length_invalid'   => 'Base64 format is invalid',
        'header_invalid'   => 'Base64 header is invalid',
        'mimetype_invalid' => 'Base64 mimetype is invalid',
    ],
    'verification' => [
        'email_sended'               => 'An verification mail has been send to your email',
        'invalid_verification_token' => 'Invalid verification token',
        'verification_not_found'     => 'Verification not found',
    ],
    'database' => [
        'table_already_exists'      => 'Table :table already exists.',
        'table_name_already_exists' => 'Table name of :table already exists.',
        'migration_failed'          => 'Migration faield to migrate.',
        'migration_dropped'         => 'Table :table successfully dropped.',
        'migration_success'         => 'Migration successfully migrated.',
        'migration_deleted'         => 'Migration successfully deleted.',
        'alter_migration_created'   => 'Alter table :table successfully created and migrated.',
        'table_not_found'           => 'Table :table does not exists',
        'nothing_changed'           => 'Request was successful, but nothing changed.',
        'rollback_success'          => 'Rollback success.',
        'rollback_failed'           => 'Rollback failed.',
        'wrong_type_data'           => 'Your data type false',
    ],
];
