<?php

return [
    'auth' => [
        'invalid_credentials'  => 'Email atau password tidak valid',
        'user_not_found'       => 'Pengguna tidak ditemukan',
        'wrong_old_password'   => 'Password lama tidak valid',
        'password_not_changes' => 'Password baru dan password lama harus berbeda',
    ],
    'bread' => [
        'table_not_found'                     => 'Table :table tidak ditemukan',
        'table_column_not_found'              => 'Row tidak valid, Field :table_column tidak ditemukan',
        'table_column_not_have_default_value' => 'Row tidak valid, Field :table_column tidak punya nilai default, tolong centang checkbox Add',
        'id_table_wrong'                      => 'Primary Key harus diganti dengan "id"',
    ],
    'base64' => [
        'length_invalid'   => 'Format base64 tidak valid',
        'header_invalid'   => 'Header base64 tidak valid',
        'mimetype_invalid' => 'Mimetype base64 tidak valid',
    ],
    'verification' => [
        'email_sended'               => 'Email verifikasi telah dikirim ke alamat email yang dimasukkan',
        'invalid_verification_token' => 'Token verifikasi tidak valid',
        'verification_not_found'     => 'Verifikasi tidak ditemukan',
    ],
    'database' => [
        'table_already_exists'      => 'Tabel :table sudah ada.',
        'table_name_already_exists' => 'Nama tabel dari :table sudah ada.',
        'migration_created'         => 'Migration berhasil dibuat dan dijalankan.',
        'migration_failed'          => 'Migration gagal dibuat dan dijalankan.',
        'migration_dropped'         => 'Tabel :table berhasil dihapus.',
        'migration_success'         => 'Migration berhasil dijalankan.',
        'migration_deleted'         => 'Migration berhasil dihapus.',
        'alter_migration_created'   => 'Alter table :table berhasil dibuat dan dijalankan.',
        'table_not_found'           => 'Table :table tidak ada.',
        'nothing_changed'           => 'Request valid, namun tidak ada perubahan yang terjadi.',
        'rollback_success'          => 'Rollback sukses.',
        'rollback_failed'           => 'Rollback gagal.',

    ],
];
