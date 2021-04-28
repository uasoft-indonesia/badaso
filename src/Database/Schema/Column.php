<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Uasoft\Badaso\Database\Types\Type;

abstract class Column
{
    public static function make(array $column, string $table_name = null)
    {
        $name = Identifier::validate($column['name'], 'Column');
        $type = $column['type'];
        $type = ($type instanceof DoctrineType) ? $type : DoctrineType::getType(trim($type['name']));
        $type->tableName = $table_name;

        $options = array_diff_key($column, ['name' => $name, 'type' => $type]);

        return new DoctrineColumn($name, $type, $options);
    }

    /**
     * @return array
     */
    public static function toArray(DoctrineColumn $column)
    {
        $column_array = $column->toArray();
        $column_array['type'] = Type::toArray($column_array['type']);
        $column_array['old_name'] = $column_array['name'];
        $column_array['null'] = $column_array['notnull'] ? 'NO' : 'YES';
        $column_array['extra'] = static::getExtra($column);
        $column_array['composite'] = false;

        return $column_array;
    }

    /**
     * @return string
     */
    protected static function getExtra(DoctrineColumn $column)
    {
        $extra = '';

        $extra .= $column->getAutoincrement() ? 'auto_increment' : '';
        // todo: Add Extra stuff like mysql 'onUpdate' etc...

        return $extra;
    }
}
