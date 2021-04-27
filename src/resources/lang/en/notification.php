<?php

return [
    'event_table' => [
        'on_create' => [
            'title' => 'Table :table_name add new row',
            'body' => 'Add new row from table :table_name by :user_name'
        ],
        'on_update' => [
            'title' => 'Table :table_name edit row',
            'body' => 'Edit row from table :table_name by :user_name'
        ],
        'on_read' => [
            'title' => 'Table :table_name read row',
            'body' => 'Read  row from table :table_name by :user_name'
        ],
        'on_delete' => [
            'title' => 'Table :table_name edit delete row',
            'body' => 'Delete row from table :table_name by :user_name'
        ],
    ],
];
