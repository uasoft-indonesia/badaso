<?php

namespace Uasoft\Badaso\Database\Schema;

use Doctrine\DBAL\Schema\ForeignKeyConstraint as DoctrineForeignKey;

abstract class ForeignKey
{
    public static function make(array $foreign_key)
    {
        // Set the local table
        $local_table = null;
        if (isset($foreign_key['local_table'])) {
            $local_table = SchemaManager::getDoctrineTable($foreign_key['local_table']);
        }

        $local_columns = $foreign_key['local_columns'];
        $foreign_table = $foreign_key['foreign_table'];
        $foreign_columns = $foreign_key['foreign_columns'];
        $options = $foreign_key['options'] ?? [];

        // Set the name
        $name = isset($foreign_key['name']) ? trim($foreign_key['name']) : '';
        if (empty($name)) {
            $table = isset($local_table) ? $local_table->getName() : null;
            $name = Index::createName($local_columns, 'foreign', $table);
        } else {
            $name = Identifier::validate($name, 'Foreign Key');
        }

        $doctrine_foreign_key = new DoctrineForeignKey(
            $local_columns,
            $foreign_table,
            $foreign_columns,
            $name,
            $options
        );

        if (isset($local_table)) {
            $doctrine_foreign_key->setLocalTable($local_table);
        }

        return $doctrine_foreign_key;
    }

    /**
     * @return array
     */
    public static function toArray(DoctrineForeignKey $fk)
    {
        return [
            'name' => $fk->getName(),
            'local_table' => $fk->getLocalTableName(),
            'local_columns' => $fk->getLocalColumns(),
            'foreign_table' => $fk->getForeignTableName(),
            'foreign_columns' => $fk->getForeignColumns(),
            'options' => $fk->getOptions(),
        ];
    }
}
