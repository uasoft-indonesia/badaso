<?php

return [
    'event_table' => [
        'oncreate' => [
            'title' => 'Table :table_name add new row',
            'body' => 'Add new row from table :table_name by :user_name',
        ],
        'onupdate' => [
            'title' => 'Table :table_name edit row',
            'body' => 'Edit row from table :table_name by :user_name',
        ],
        'onread' => [
            'title' => 'Table :table_name read row',
            'body' => 'Read  row from table :table_name by :user_name',
        ],
        'ondelete' => [
            'title' => 'Table :table_name edit delete row',
            'body' => 'Delete row from table :table_name by :user_name',
        ],
    ],
];
