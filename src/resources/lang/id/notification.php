<?php

return [
    'event_table' => [
        'oncreate' => [
            'title' => 'Tabel :nama_tabel tambahan baris baru',
            'body' => 'Tambahkan baris baru dari tabel :table_name oleh :user_name',
        ],
        'onupdate' => [
            'title' => 'Tabel :nama_tabel di edit',
            'body' => 'Edit baris dari tabel :table_name oleh :user_name',
        ],
        'onread' => [
            'title' => 'Tabel :nama_tabel di baca',
            'body' => 'Baca baris dari tabel :table_name oleh :user_name',
        ],
        'ondelete' => [
            'title' => 'Tabel :nama_tabel di delete',
            'body' => 'Delete baris dari tabel :table_name oleh :user_name',
        ],
    ],
];
