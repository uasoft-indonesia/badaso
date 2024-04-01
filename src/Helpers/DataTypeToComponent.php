<?php

namespace Uasoft\Badaso\Helpers;

class DataTypeToComponent
{
    //MYSQL
    protected static $type_list = [
        'CHAR' => 'text',
        'VARCHAR' => 'text',
        'BINARY' => 'text',
        'VARBINARY' => 'text',
        'TINYBLOB' => 'text',
        'TINYTEXT' => 'text',
        'TEXT' => 'textarea',
        'BLOB' => 'text',
        'MEDIUMTEXT' => 'text',
        'MEDIUMBLOB' => 'text',
        'LONGTEXT' => 'text',
        'LONGBLOB' => 'text',
        'CHARACTER VARYING' => 'text',
        'CHARACTER' => 'text',
        'BYTEA' => 'text',
        'JSON' => 'text',
        'ENUM' => 'select',
        'SET' => 'select_multiple',
        'BIT' => 'text',
        'TINYINT' => 'number',
        'BOOL' => 'switch',
        'BOOLEAN' => 'switch',
        'SMALLINT' => 'number',
        'MEDIUMINT' => 'number',
        'INT' => 'number',
        'INTEGER' => 'number',
        'BIGINT' => 'number',
        'FLOAT' => 'number',
        'DECIMAL' => 'number',
        'DEC' => 'number',
        'YEAR' => 'number',
        'NUMERIC' => 'number',
        'DOUBLE' => 'number',
        'DOUBLE PRECISION' => 'number',
        'DATETIME' => 'datetime',
        'TIMESTAMP' => 'datetime',
        'TIME' => 'datetime',
    ];

    public static function convert($type)
    {
        if (array_key_exists(strtoupper($type), self::$type_list)) {
            return self::$type_list[strtoupper($type)];
        } else {
            return $type;
        }
    }

    private static function reverse($type)
    {
    }
}
