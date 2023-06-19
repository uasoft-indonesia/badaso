<?php

namespace Uasoft\Badaso\Database;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\TableDiff;
use Uasoft\Badaso\Database\Schema\SchemaManager;
use Uasoft\Badaso\Database\Schema\Table;
use Uasoft\Badaso\Database\Types\Type;

class DatabaseUpdater
{
    protected $table_array;
    protected $table;
    protected $original_table;

    public function __construct(array $table_array)
    {
        Type::registerCustomPlatformTypes();

        $this->table = Table::make($table_array);
        $this->table_array = $table_array;
        $this->original_table = SchemaManager::listTableDetails($table_array['old_name']);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public static function update($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        if (! SchemaManager::tableExists($table['old_name'])) {
            throw SchemaException::tableDoesNotExist($table['old_name']);
        }

        $updater = new self($table);

        $updater->updateTable();
    }

    /**
     * Updates the table.
     *
     * @return void
     */
    public function updateTable()
    {
        // Get table new name
        if (($new_name = $this->table->getName()) != $this->original_table->getName()) {
            // Make sure the new name doesn't already exist
            if (SchemaManager::tableExists($new_name)) {
                throw SchemaException::tableAlreadyExists($new_name);
            }
        } else {
            $new_name = false;
        }

        // Rename columns
        if ($renamed_columns_diff = $this->getRenamedColumnsDiff()) {
            SchemaManager::alterTable($renamed_columns_diff);

            // Refresh original table after renaming the columns
            $this->original_table = SchemaManager::listTableDetails($this->table_array['old_name']);
        }

        $table_diff = $this->original_table->diff($this->table);

        // Add new table name to table_diff
        if ($new_name) {
            if (! $table_diff) {
                $table_diff = new TableDiff($this->table_array['old_name']);
                $table_diff->fromTable = $this->original_table;
            }

            $table_diff->new_name = $new_name;
        }

        // Update the table
        if ($table_diff) {
            SchemaManager::alterTable($table_diff);
        }
    }

    /**
     * Get the table diff to rename columns.
     *
     * @return \Doctrine\DBAL\Schema\TableDiff
     */
    protected function getRenamedColumnsDiff()
    {
        $renamed_columns = $this->getRenamedColumns();

        if (empty($renamed_columns)) {
            return false;
        }

        $renamed_columns_diff = new TableDiff($this->table_array['old_name']);
        $renamed_columns_diff->fromTable = $this->original_table;

        foreach ($renamed_columns as $old_name => $new_name) {
            $renamed_columns_diff->renamed_columns[$old_name] = $this->table->getColumn($new_name);
        }

        return $renamed_columns_diff;
    }

    /**
     * Get the table diff to rename columns and indexes.
     *
     * @return \Doctrine\DBAL\Schema\TableDiff
     */
    protected function getRenamedDiff()
    {
        $renamed_columns = $this->getRenamedColumns();
        $renamed_indexes = $this->getRenamedIndexes();

        if (empty($renamed_columns) && empty($renamed_indexes)) {
            return false;
        }

        $renamed_diff = new TableDiff($this->table_array['old_name']);
        $renamed_diff->fromTable = $this->original_table;

        foreach ($renamed_columns as $old_name => $new_name) {
            $renamed_diff->renamed_columns[$old_name] = $this->table->getColumn($new_name);
        }

        foreach ($renamed_indexes as $old_name => $new_name) {
            $renamed_diff->renamed_indexes[$old_name] = $this->table->getIndex($new_name);
        }

        return $renamed_diff;
    }

    /**
     * Get columns that were renamed.
     *
     * @return array
     */
    protected function getRenamedColumns()
    {
        $renamed_columns = [];

        foreach ($this->table_array['columns'] as $column) {
            $old_name = $column['old_name'];

            // make sure this is an existing column and not a new one
            if ($this->original_table->hasColumn($old_name)) {
                $name = $column['name'];

                if ($name != $old_name) {
                    $renamed_columns[$old_name] = $name;
                }
            }
        }

        return $renamed_columns;
    }

    /**
     * Get indexes that were renamed.
     *
     * @return array
     */
    protected function getRenamedIndexes()
    {
        $renamed_indexes = [];

        foreach ($this->table_array['indexes'] as $index) {
            $old_name = $index['old_name'];

            // make sure this is an existing index and not a new one
            if ($this->original_table->hasIndex($old_name)) {
                $name = $index['name'];

                if ($name != $old_name) {
                    $renamed_indexes[$old_name] = $name;
                }
            }
        }

        return $renamed_indexes;
    }
}
