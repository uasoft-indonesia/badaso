<?php

namespace Uasoft\Badaso\Database\Platforms;

use Illuminate\Support\Collection;

abstract class Sqlite extends Platform
{
    public static function getTypes(Collection $type_mapping)
    {
        $type_mapping->forget([
            'decimal',
            'double',
        ]);

        return $type_mapping->unique();
    }

    public static function registerCustomTypeOptions()
    {
    }
}
