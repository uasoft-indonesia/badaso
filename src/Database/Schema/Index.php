<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\Index as DoctrineIndex;

abstract class Index
{
    const PRIMARY = 'PRIMARY';
    const UNIQUE = 'UNIQUE';
    const INDEX = 'INDEX';

    public static function make(array $index)
    {
        $columns = $index['columns'];
        if (! is_array($columns)) {
            $columns = [$columns];
        }

        if (isset($index['type'])) {
            $type = $index['type'];

            $is_primary = ($type == static::PRIMARY);
            $is_unique = $is_primary || ($type == static::UNIQUE);
        } else {
            $is_primary = $index['is_primary'];
            $is_unique = $index['is_unique'];

            // Set the type
            if ($is_primary) {
                $type = static::PRIMARY;
            } elseif ($is_unique) {
                $type = static::UNIQUE;
            } else {
                $type = static::INDEX;
            }
        }

        // Set the name
        $name = trim($index['name'] ?? '');
        if (empty($name)) {
            $table = $index['table'] ?? null;
            $name = static::createName($columns, $type, $table);
        } else {
            $name = Identifier::validate($name, 'Index');
        }

        $flags = $index['flags'] ?? [];
        $options = $index['options'] ?? [];

        return new DoctrineIndex($name, $columns, $is_unique, $is_primary, $flags, $options);
    }

    /**
     * @return array
     */
    public static function toArray(DoctrineIndex $index)
    {
        $name = $index->getName();
        $columns = $index->getColumns();

        return [
            'name' => $name,
            'oldName' => $name,
            'columns' => $columns,
            'type' => static::getType($index),
            'is_primary' => $index->isPrimary(),
            'is_unique' => $index->isUnique(),
            'isComposite' => count($columns) > 1,
            'flags' => $index->getFlags(),
            'options' => $index->getOptions(),
        ];
    }

    public static function getType(DoctrineIndex $index)
    {
        if ($index->isPrimary()) {
            return static::PRIMARY;
        } elseif ($index->isUnique()) {
            return static::UNIQUE;
        } else {
            return static::INDEX;
        }
    }

    /**
     * Create a default index name.
     *
     * @param  array  $columns
     * @param  string  $type
     * @param  string  $table
     * @return string
     */
    public static function createName(array $columns, $type, $table = null)
    {
        $table = isset($table) ? trim($table).'_' : '';
        $type = trim($type);
        $name = strtolower($table.implode('_', $columns).'_'.$type);

        return str_replace(['-', '.'], '_', $name);
    }

    public static function availableTypes()
    {
        return [
            static::PRIMARY,
            static::UNIQUE,
            static::INDEX,
        ];
    }
}
