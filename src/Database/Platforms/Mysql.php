<?php

namespace Uasoft\Badaso\Database\Platforms;

use Illuminate\Support\Collection;
use Uasoft\Badaso\Database\Types\Type;

abstract class Mysql extends Platform
{
    public static function getTypes(Collection $type_mapping)
    {
        $type_mapping->forget([
            'real',    // same as double
            'int',     // same as integer
            'string',  // same as varchar
            'numeric', // same as decimal
        ]);

        return $type_mapping;
    }

    public static function registerCustomTypeOptions()
    {
        // Not supported
        Type::registerCustomOption(Type::NOT_SUPPORTED, true, [
            'enum',
            'set',
        ]);

        // Not support index
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*text');
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*blob');

        // Disable Default for unsupported types
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], '*text');
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], '*blob');
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], 'json');
    }
}
