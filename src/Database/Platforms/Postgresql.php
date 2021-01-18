<?php

namespace Uasoft\Badaso\Database\Platforms;

use Illuminate\Support\Collection;

abstract class Postgresql extends Platform
{
    public static function getTypes(Collection $type_mapping)
    {
        // todo: need to create
        // box, circle, line, lseg, path, pg_lsn, point, polygon

        $type_mapping->forget([
            'smallint',
            'serial',
            'serial4',
            'int',
            'integer',
            'bigserial',
            'serial8',
            'bigint',
            'decimal',
            'float',
            'real',
            'double',
            'double precision',
            'boolean',
            '_varchar',
            'char',
            'datetime',
            'year',
        ]);

        return $type_mapping;
    }

    public static function registerCustomTypeOptions()
    {
    }
}
