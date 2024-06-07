<?php

namespace Uasoft\Badaso\Database\Types;

use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Database\Platforms\Platform;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Doctrine\DBAL\Platforms\AbstractPlatform as DoctrineAbstractPlatform;

abstract class Type extends DoctrineType
{
    protected static $custom_type_registered = false;
    protected static $platform_type_mapping = [];
    protected static $all_types = [];
    protected static $platform_types = [];
    protected static $custom_type_options = [];
    protected static $type_categories = [];
    protected static $types = [];

    const NAME = 'UNDEFINED_TYPE_NAME';
    const NOT_SUPPORTED = 'notSupported';
    const NOT_SUPPORT_INDEX = 'notSupportIndex';

    // todo: make sure this is not overwrting DoctrineType properties

    // Note: length, precision and scale need default values manually

    public function getName()
    {
        return static::NAME;
    }

    public static function toArray($type)
    {
        $custom_type_options = $type->customOptions ?? [];

        return array_merge([
            'name' => $type->getName(),
        ], $custom_type_options);
    }

    public static function getPlatformTypes()
    {
        if (static::$platform_types) {
            return static::$platform_types;
        }

        if (!static::$custom_type_registered) {
            static::registerCustomPlatformTypes();
        }

        $platform_name = DB::getDriverName();

        static::$platform_types = Platform::getPlatformTypes(
            $platform_name,
            static::getPlatformTypeMapping($platform_name)
        );

        static::$platform_types = static::$platform_types->map(function ($type) {
            return static::toArray(static::getType($type));
        })->groupBy('category');

        return static::$platform_types;
    }

    public static function getPlatformTypeMapping(DoctrineAbstractPlatform $platform_name)
    {
        if (static::$platform_type_mapping) {
            return static::$platform_type_mapping;
        }

        // Anda perlu mendefinisikan cara mengambil pemetaan tipe khusus untuk platform tertentu
        static::$platform_type_mapping = collect(
            get_protected_property($platform_name, 'doctrineTypeMapping')
        );

        return static::$platform_type_mapping;
    }

    public static function registerCustomPlatformTypes($force = false)
    {
        if (static::$custom_type_registered && !$force) {
            return;
        }

        $platform_name = DB::getDriverName();
     
        $custom_types = array_merge(
            static::getPlatformCustomTypes('Common'),
            static::getPlatformCustomTypes($platform_name)
        );
   
        foreach ($custom_types as $type) {
            $name = $type::NAME;

            if (!static::hasType($name)) {
                static::addType($name, $type);
            } else {
                static::overrideType($name, $type);
            }

            $db_type = defined("{$type}::DBTYPE") ? $type::DBTYPE : $name;
   
            static::registerDoctrineTypeMapping($db_type, $name);
        }

        static::addCustomTypeOptions($platform_name);

        static::$custom_type_registered = true;
    }

    protected static function registerDoctrineTypeMapping($db_type, $name)
    {
        $databaseConfig = config('database.connections.' . config('database.default'));

        $connectionParams = [
            'dbname' => $databaseConfig['database'],
            'user' => $databaseConfig['username'],
            'password' => $databaseConfig['password'],
            'host' => $databaseConfig['host'],
            'driver' => 'pdo_mysql', // Sesuaikan dengan driver yang digunakan, misal: 'pdo_pgsql' untuk PostgreSQL
            'port' => $databaseConfig['port'],
        ];

        $doctrine_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        $platform = $doctrine_connection->getDatabasePlatform();
      
        if ($platform->hasDoctrineTypeMappingFor($db_type)) {
            $platform->registerDoctrineTypeMapping($db_type, $name);
        } else {
            throw new \Doctrine\DBAL\Exception("Type to be overwritten $db_type does not exist.");
        }
    }

    protected static function addCustomTypeOptions($platform_name)
    {
        static::registerCommonCustomTypeOptions();

        Platform::registerPlatformCustomTypeOptions($platform_name);

        // Add the custom options to the types
        foreach (static::$custom_type_options as $option) {
            foreach ($option['types'] as $type) {
                if (static::hasType($type)) {
                    static::getType($type)->customOptions[$option['name']] = $option['value'];
                }
            }
        }
    }

    protected static function getPlatformCustomTypes($platform_name)
    {
        $types_path = __DIR__ . DIRECTORY_SEPARATOR . $platform_name . DIRECTORY_SEPARATOR;
        $namespace = __NAMESPACE__ . '\\' . $platform_name . '\\';
        $types = [];

        foreach (glob($types_path . '*.php') as $class_file) {
            $types[] = $namespace . str_replace(
                '.php',
                '',
                str_replace($types_path, '', $class_file)
            );
        }

        return $types;
    }

    public static function registerCustomOption($name, $value, $types)
    {
        if (is_string($types)) {
            $types = trim($types);

            if ($types == '*') {
                $types = static::getAllTypes()->toArray();
            } elseif (strpos($types, '*') !== false) {
                $search_type = str_replace('*', '', $types);
                $types = static::getAllTypes()->filter(function ($type) use ($search_type) {
                    return strpos($type, $search_type) !== false;
                })->toArray();
            } else {
                $types = [$types];
            }
        }

        static::$custom_type_options[] = [
            'name' => $name,
            'value' => $value,
            'types' => $types,
        ];
    }

    protected static function registerCommonCustomTypeOptions()
    {
        static::registerTypeCategories();
        static::registerTypeDefaultOptions();
    }

    protected static function registerTypeDefaultOptions()
    {
        $types = static::getTypeCategories();

        // Numbers
        static::registerCustomOption('default', [
            'type' => 'number',
            'step' => 'any',
        ], $types['numbers']);

        // Date and Time
        static::registerCustomOption('default', [
            'type' => 'date',
        ], 'date');
        static::registerCustomOption('default', [
            'type' => 'time',
            'step' => '1',
        ], 'time');
        static::registerCustomOption('default', [
            'type' => 'number',
            'min' => '0',
        ], 'year');
    }

    protected static function registerTypeCategories()
    {
        $types = static::getTypeCategories();

        static::registerCustomOption('category', 'Numbers', $types['numbers']);
        static::registerCustomOption('category', 'Strings', $types['strings']);
        static::registerCustomOption('category', 'Date and Time', $types['datetime']);
        static::registerCustomOption('category', 'Lists', $types['lists']);
        static::registerCustomOption('category', 'Binary', $types['binary']);
        static::registerCustomOption('category', 'Geometry', $types['geometry']);
        static::registerCustomOption('category', 'Network', $types['network']);
        static::registerCustomOption('category', 'Objects', $types['objects']);
    }

    public static function getAllTypes()
    {
        if (static::$all_types) {
            return static::$all_types;
        }

        static::$all_types = collect(static::getTypeCategories())->flatten();

        return static::$all_types;
    }

    public static function getTypeCategories()
    {
        if (static::$type_categories) {
            return static::$type_categories;
        }

        $numbers = [
            'boolean',
            'tinyint',
            'smallint',
            'mediumint',
            'integer',
            'int',
            'bigint',
            'decimal',
            'numeric',
            'money',
            'float',
            'real',
            'double',
            'double precision',
        ];

        $strings = [
            'char',
            'character',
            'varchar',
            'character varying',
            'string',
            'guid',
            'uuid',
            'tinytext',
            'text',
            'mediumtext',
            'longtext',
            'tsquery',
            'tsvector',
            'xml',
        ];

        $datetime = [
            'date',
            'datetime',
            'year',
            'time',
            'timetz',
            'timestamp',
            'timestamptz',
            'datetimetz',
            'dateinterval',
            'interval',
        ];

        $lists = [
            'enum',
            'set',
            'simple_array',
            'array',
            'json',
            'jsonb',
            'json_array',
        ];

        $binary = [
            'bit',
            'bit varying',
            'binary',
            'varbinary',
            'tinyblob',
            'blob',
            'mediumblob',
            'longblob',
            'bytea',
        ];

        $network = [
            'cidr',
            'inet',
            'macaddr',
            'txid_snapshot',
        ];

        $geometry = [
            'geometry',
            'point',
            'linestring',
            'polygon',
            'multipoint',
            'multilinestring',
            'multipolygon',
            'geometrycollection',
        ];

        $objects = [
            'object',
        ];

        static::$type_categories = [
            'numbers' => $numbers,
            'strings' => $strings,
            'datetime' => $datetime,
            'lists' => $lists,
            'binary' => $binary,
            'network' => $network,
            'geometry' => $geometry,
            'objects' => $objects,
        ];

        return static::$type_categories;
    }

    public static function hasType($name)
    {
        return DoctrineType::hasType($name);
    }

    public static function getType($name)
    {
        return static::$types[$name] ?? null;
    }

    public static function addType($name, $type)
    {
        static::$types[$name] = new $type();
    }

    public static function overrideType($name, $type)
    {
        static::$types[$name] = new $type();
    }
}
