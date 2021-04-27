<?php

return [
    'event_table' => [
        'on_create' => [
            'title' => 'Tabel :nama_tabel tambahan baris baru',
            'body' => 'Tambahkan baris baru dari tabel :table_name oleh :user_name'
        ],
        'on_update' => [
            'title' => 'Tabel :nama_tabel di edit',
            'body' => 'Edit baris dari tabel :table_name oleh :user_name'
        ],
        'on_read' => [
            'title' => 'Tabel :nama_tabel di baca',
            'body' => 'Baca baris dari tabel :table_name oleh :user_name'
        ],
        'on_delete' => [
            'title' => 'Tabel :nama_tabel di delete',
            'body' => 'Delete baris dari tabel :table_name oleh :user_name'
        ],
    ],
];
