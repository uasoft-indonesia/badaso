<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\Table;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Uasoft\Badaso\Database\Types\Type;

abstract class SchemaManager
{
    public static function __callStatic($method, $args)
    {
        return Schema::$method(...$args);
    }

    public static function tableExists($table)
    {
        if (!is_array($table)) {
            $table = [$table];
        }

        foreach ($table as $tbl) {
            if (!Schema::hasTable($tbl)) {
                return false;
            }
        }

        return true;
    }

    public static function listTables()
    {
        $tables = DB::select('SHOW TABLES');
        $database_name = DB::getDatabaseName();
        $tables_key = "Tables_in_$database_name";

        $result = [];
        foreach ($tables as $table) {
            $table_name = $table->$tables_key;
            $result[$table_name] = static::listTableDetails($table_name);
        }

        return $result;
    }

    public static function listTableDetails($table_name)
    {
        $columns = Schema::getColumnListing($table_name);

        // $foreign_keys = static::listTableForeignKeys($table_name);
        $foreign_keys[] = Schema::getForeignKeys($table_name);
        // $indexes = Schema::listTableIndexes($table_name);
        $indexes[] = Schema::getIndexes($table_name);
        return new Table($table_name, $columns, $indexes, $foreign_keys, []);
    }

    public static function getColumnDetails($table_name, $column_name)
    {
        return DB::select(DB::raw("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'"))[0];
    }

    public static function describeTable($table_name)
    {
        // Type::registerCustomPlatformTypes();

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

        return Schema::getColumnListing($table_name);
    }

    public static function createTable($table)
    {
        if (!($table instanceof Blueprint)) {
            $table = Table::make($table);
        }

        Schema::create($table->getTable(), function (Blueprint $blueprint) use ($table) {
            foreach ($table->getColumns() as $column) {
                $blueprint->addColumn($column->getType(), $column->getName(), $column->getOptions());
            }

            foreach ($table->getIndexes() as $index) {
                $blueprint->index($index->getColumns(), $index->getName(), $index->getOptions());
            }

            foreach ($table->getForeignKeys() as $foreignKey) {
                $blueprint->foreign($foreignKey->getLocalColumns())
                    ->references($foreignKey->getForeignColumns())
                    ->on($foreignKey->getForeignTable())
                    ->onDelete($foreignKey->onDelete())
                    ->onUpdate($foreignKey->onUpdate());
            }
        });
    }

    public static function getDoctrineTable($table)
    {
        $table = trim($table);

        if (!static::tableExists($table)) {
            throw new \Exception("Table $table does not exist.");
        }

        return static::listTableDetails($table);
    }

    public static function getDoctrineColumn($table, $column)
    {
        return static::getColumnDetails($table, $column);
    }

    public static function listTableForeignKeys($table_name)
    {
        $foreignKeys = DB::select(DB::raw("SELECT * FROM information_schema.key_column_usage WHERE TABLE_NAME = '$table_name' AND TABLE_SCHEMA = '" . DB::getDatabaseName() . "' AND REFERENCED_COLUMN_NAME IS NOT NULL"));
        return $foreignKeys;
    }

    public static function listTableIndexes($table_name)
    {
        $indexes = DB::select(DB::raw("SHOW INDEX FROM `$table_name`"));
        return $indexes;
    }
}
