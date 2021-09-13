<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Database\Types\Type;

abstract class SchemaManager
{
    // todo: trim parameters

    public static function __callStatic($method, $args)
    {
        return static::manager()->$method(...$args);
    }

    public static function manager()
    {
        return DB::connection()->getDoctrineSchemaManager();
    }

    public static function getDatabaseConnection()
    {
        return DB::connection()->getDoctrineConnection();
    }

    public static function tableExists($table)
    {
        if (! is_array($table)) {
            $table = [$table];
        }

        return static::manager()->tablesExist($table);
    }

    public static function listTables()
    {
        $tables = [];

        foreach (static::manager()->listTableNames() as $table_name) {
            $tables[$table_name] = static::listTableDetails($table_name);
        }

        return $tables;
    }

    /**
     * @param  string  $table_name
     * @return \Uasoft\Badaso\Database\Schema\Table
     */
    public static function listTableDetails($table_name)
    {
        $columns = static::manager()->listTableColumns($table_name);

        $foreign_keys = [];
        if (static::manager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
            $foreign_keys = static::manager()->listTableForeignKeys($table_name);
        }

        $indexes = static::manager()->listTableIndexes($table_name);

        return new Table($table_name, $columns, $indexes, $foreign_keys, false, []);
    }

    /**
     * Describes given table.
     *
     * @param  string  $table_name
     * @return \Illuminate\Support\Collection
     */
    public static function describeTable($table_name)
    {
        Type::registerCustomPlatformTypes();

        $table = static::listTableDetails($table_name);

        return collect($table->columns)->map(function ($column) use ($table) {
            $column_array = Column::toArray($column);

            $column_array['field'] = $column_array['name'];
            $column_array['type'] = $column_array['type']['name'];

            // Set the indexes and key
            $column_array['indexes'] = [];
            $column_array['key'] = null;
            if ($column_array['indexes'] = $table->getColumnsIndexes($column_array['name'], true)) {
                // Convert indexes to Array
                foreach ($column_array['indexes'] as $name => $index) {
                    $column_array['indexes'][$name] = Index::toArray($index);
                }

                // If there are multiple indexes for the column
                // the Key will be one with highest priority
                $indexType = array_values($column_array['indexes'])[0]['type'];
                $column_array['key'] = substr($indexType, 0, 3);
            }

            return $column_array;
        });
    }

    public static function listTableColumnNames($table_name)
    {
        Type::registerCustomPlatformTypes();

        $column_names = [];

        foreach (static::manager()->listTableColumns($table_name) as $column) {
            $column_names[] = $column->getName();
        }

        return $column_names;
    }

    public static function createTable($table)
    {
        if (! ($table instanceof DoctrineTable)) {
            $table = Table::make($table);
        }

        static::manager()->createTable($table);
    }

    public static function getDoctrineTable($table)
    {
        $table = trim($table);

        if (! static::tableExists($table)) {
            throw SchemaException::tableDoesNotExist($table);
        }

        return static::manager()->listTableDetails($table);
    }

    public static function getDoctrineColumn($table, $column)
    {
        return static::getDoctrineTable($table)->getColumn($column);
    }

    public static function getDoctrineForeignKeys($table)
    {
        return static::manager()->listTableForeignKeys($table);
    }
}
