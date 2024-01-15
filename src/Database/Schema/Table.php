<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Table as DoctrineTable;

class Table extends DoctrineTable
{
    public static function make($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        $name = Identifier::validate($table['name'], 'Table');

        $columns = [];
        foreach ($table['columns'] as $column_array) {
            $column = Column::make($column_array, $table['name']);
            $columns[$column->getName()] = $column;
        }

        $indexes = [];
        foreach ($table['indexes'] as $index_array) {
            $index = Index::make($index_array);
            $indexes[$index->getName()] = $index;
        }

        $foreign_keys = [];
        foreach ($table['foreign_keys'] as $foreign_key_array) {
            $foreign_key = ForeignKey::make($foreign_key_array);
            $foreign_keys[$foreign_key->getName()] = $foreign_key;
        }

        $options = $table['options'];

        return new self($name, $columns, $indexes, $foreign_keys, false, $options);
    }

    public function getColumnsIndexes($columns, $sort = false)
    {
        if (! is_array($columns)) {
            $columns = [$columns];
        }

        $matched = [];

        foreach ($this->_indexes as $index) {
            if ($index->spansColumns($columns)) {
                $matched[$index->getName()] = $index;
            }
        }

        if (count($matched) > 1 && $sort) {
            // Sort indexes based on priority: PRI > UNI > IND
            uasort($matched, function ($index1, $index2) {
                $index1_type = Index::getType($index1);
                $index2_type = Index::getType($index2);

                if ($index1_type == $index2_type) {
                    return 0;
                }

                if ($index1_type == Index::PRIMARY) {
                    return -1;
                }

                if ($index2_type == Index::PRIMARY) {
                    return 1;
                }

                if ($index1_type == Index::UNIQUE) {
                    return -1;
                }

                // If we reach here, it means: $index1=INDEX && $index2=UNIQUE
                return 1;
            });
        }

        return $matched;
    }

    public function diff(DoctrineTable $compare_table)
    {
        return (new Comparator())->diffTable($this, $compare_table);
    }

    public function diffOriginal()
    {
        return (new Comparator())->diffTable(SchemaManager::getDoctrineTable($this->_name), $this);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->_name,
            'oldName' => $this->_name,
            'columns' => $this->exportColumnsToArray(),
            'indexes' => $this->exportIndexesToArray(),
            'primaryKeyName' => $this->_primaryKeyName,
            'foreign_keys' => $this->exportForeignKeysToArray(),
            'options' => $this->_options,
        ];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function exportColumnsToArray()
    {
        $exported_columns = [];

        foreach ($this->getColumns() as $name => $column) {
            $exported_columns[] = Column::toArray($column);
        }

        return $exported_columns;
    }

    /**
     * @return array
     */
    public function exportIndexesToArray()
    {
        $exported_indexes = [];

        foreach ($this->getIndexes() as $name => $index) {
            $index_array = Index::toArray($index);
            $index_array['table'] = $this->_name;
            $exported_indexes[] = $index_array;
        }

        return $exported_indexes;
    }

    /**
     * @return array
     */
    public function exportForeignKeysToArray()
    {
        $exported_foreign_keys = [];

        foreach ($this->getForeignKeys() as $name => $fk) {
            $exported_foreign_keys[$name] = ForeignKey::toArray($fk);
        }

        return $exported_foreign_keys;
    }

    public function __get($property)
    {
        $getter = 'get'.ucfirst($property);

        if (! method_exists($this, $getter)) {
            throw new \Exception("Property {$property} doesn't exist or is unavailable");
        }

        return $this->$getter();
    }
}
