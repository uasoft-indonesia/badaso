<?php

namespace Uasoft\Badaso\Helpers;

class MigrationParser
{
    const FIELD_STUB = <<<'TXT'
    $table->%s('%s'%s)%s%s%s%s%s;
    TXT;

    const CHANGE_FIELD_STUB = <<<'TXT'
    $table->%s('%s'%s)%s%s%s%s%s->charset('')->collation('')->change();
    TXT;

    const FIELD_DECIMAL_LENGTH = <<<'TXT'
    , %s
    TXT;

    const FIELD_ARRAY_LENGTH = <<<'TXT'
    , [%s]
    TXT;

    const FIELD_ARRAY_LENGTH_CONTENT = <<<'TXT'
    '%s',
    TXT;

    const FIELD_ARRAY_LENGTH_CONTENT_LAST = <<<'TXT'
    '%s'
    TXT;

    const FIELD_DEFAULT = <<<'TXT'
    ->default('%s')
    TXT;

    const FIELD_DEFAULT_DECIMAL = <<<'TXT'
    ->default(%s)
    TXT;

    const FIELD_NULLABLE = <<<'TXT'
    ->nullable(%s)
    TXT;

    const FIELD_INDEX = <<<'TXT'
    %s('%s')
    TXT;

    const FIELD_ATTRIBUTE = <<<'TXT'
    ->%s()
    TXT;

    const REMOVE_UNSIGNED = <<<'TXT'
    ->unsigned(false)
    TXT;

    const FIELD_INCREMENT = <<<'TXT'
    ->autoIncrement()
    TXT;

    const MIGRATION_UP_WRAPPER = <<<'TXT'
            Schema::create('%s', function (Blueprint $table) {
                %s
            });
    TXT;

    const RENAME_TABLE_WRAPPER = <<<'TXT'
    Schema::rename('%s', '%s');
    TXT;

    const ALTER_WRAPPER = <<<'TXT'
            Schema::table('%s', function (Blueprint $table) {
                %s
            });
    TXT;

    const RENAME_WRAPPER = <<<'TXT'
            Schema::table('%s', function (Blueprint $table) {
                %s
            });
    TXT;

    const RENAME_COLUMN = <<<'TXT'
    $table->renameColumn('%s', '%s');
    TXT;

    const CHANGE_COLUMN = <<<'TXT'
    $table->%s('%s'%s)%s->charset('')->collation('')->change();
    TXT;

    const DROP_PRIMARY_KEY = <<<'TXT'
    $table->dropPrimary('%s');
    TXT;

    const ADD_PRIMARY_KEY = <<<'TXT'
    $table->primary('%s');
    TXT;

    const CHANGE_COLUMN_LENGTH = <<<'TXT'
    $table->%s('%s'%s)->charset('')->collation('')->change();
    TXT;

    const MIGRATION_DOWN_WRAPPER = <<<'TXT'
            Schema::dropIfExists('%s');
    TXT;

    const FIELD_DEFAULT_CURRENT_TIMESTAMP = <<<'TXT'
    ->useCurrent()
    TXT;

    const FIELD_DEFAULT_NULL = <<<'TXT'
    ->default(null)
    TXT;

    const DROP_FIELD = <<<'TXT'
    $table->dropColumn('%s');
    TXT;

    const DROP_INDEX = <<<'TXT'
    $table->drop%s('%s');
    TXT;

    const DROP_INDEX_TABLE = <<<'TXT'
    $table->drop%s('%s_%s_%s');
    TXT;

    const CREATE_INDEX = <<<'TXT'
    $table->%s;
    TXT;

    const TIMESTAMP = <<<'TXT'
    $table->timestamps();
    TXT;

    const SOFT_DELETE = <<<'TXT'
    $table->softDeletes();
    TXT;

    const DROP_AI = <<<'TXT'
    $table->%s('%s'%s)%s->charset('')->collation('')->change();
    TXT;

    const ADD_AI = <<<'TXT'
    $table->%s('%s'%s)%s%s->charset('')->collation('')->change();
    TXT;

    const DROP_DEFAULT = <<<'TXT'
    DB::statement('ALTER TABLE %s ALTER COLUMN %s DROP DEFAULT');
    TXT;

    const ADD_FOREIGN_KEY = <<<'TXT'
    $table->foreign('%s')->references('%s')->on('%s')
    TXT;

    const DROP_FOREIGN_KEY = <<<'TXT'
    $table->dropForeign([%s]);
    TXT;

    const ADD_FOREIGN_KEY_ON_DELETE = <<<'TXT'
    ->onDelete('%s')
    TXT;

    const ADD_FOREIGN_KEY_ON_UPDATE = <<<'TXT'
    ->onUpdate('%s')
    TXT;

    public static function getMigrationSchemaUp($name, $rows, $prefix = null)
    {
        if ($prefix == 'drop') {
            return sprintf(
                self::MIGRATION_DOWN_WRAPPER,
                $name,
            );
        }

        return sprintf(self::MIGRATION_UP_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), self::getMigrationFields($name, $rows)));
    }

    public static function getMigrationRelationshipSchemaUp($name, $relations)
    {
        return sprintf(self::ALTER_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), self::getMigrationRelationshipUp($name, $relations)));
    }

    public static function getAlterMigrationRelationshipSchemaUp($name, $relations)
    {
        if (implode(PHP_EOL.chr(9).chr(9).chr(9), self::getAlterMigrationRelationshipUp($relations)) !== '') {
            return sprintf(self::ALTER_WRAPPER,
                $name['current_name'],
                implode(PHP_EOL.chr(9).chr(9).chr(9), self::getAlterMigrationRelationshipUp($relations))
            );
        }
    }

    public static function getMigrationRelationshipSchemaDown($name, $relations)
    {
        return sprintf(self::ALTER_WRAPPER, $name, self::getMigrationRelationshipDown($relations));
    }

    public static function getAlterMigrationRelationshipSchemaDown($name, $relations)
    {
        if (implode(PHP_EOL.chr(9).chr(9).chr(9), self::getAlterMigrationRelationshipDown($relations)) !== '') {
            return sprintf(self::ALTER_WRAPPER,
                $name['current_name'],
                implode(PHP_EOL.chr(9).chr(9).chr(9), self::getAlterMigrationRelationshipDown($relations))
            );
        }
    }

    public static function getMigrationSchemaDown($name, $rows = [], $prefix = null, bool $timestamp = true)
    {
        if ($prefix == 'drop') {
            return sprintf(self::MIGRATION_UP_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), self::getMigrationFields($name, $rows)));
        }

        return sprintf(
            self::MIGRATION_DOWN_WRAPPER,
            $name,
        );
    }

    public static function getAlterMigrationSchemaUp($name, $rows, $prefix = null, $relations = [])
    {
        $stub = '';
        $dropped_fk_field = '';

        if ($prefix == 'rename') {
            $stub .= chr(9).chr(9).chr(9).sprintf(
                self::RENAME_TABLE_WRAPPER,
                $name['current_name'],
                $name['modified_name'],
            );
        } else {
            if (isset($rows)) {
                /**
                 * Here we separate field based on modify type of fields.
                 */
                $dropped_field = 0;
                $altered_field = 0;
                $added_field = 0;
                $dropped_fk = 0;
                $fields = [];
                $modified = [];
                $alter = [];
                $fk = [];

                $rows = self::formatRows($rows);

                if (! empty($relations)) {
                    foreach ($relations['modified_relations'] as $key => $relation) {
                        if (array_key_exists('modify_type', $relation) && $relation['modify_type'] === 'DROP_FOREIGN_KEY') {
                            $dropped_fk++;
                            $fk[] = $relation;
                        }
                    }
                }

                foreach ($rows['current_fields'] as $key => $value) {
                    if (! array_key_exists($key, $rows['modified_fields'])) {
                        $dropped_field++;
                        $rows['modified_fields'][$key] = $value;
                        $rows['modified_fields'][$key]['modify_type'] = ['DROP_FIELD'];
                    }
                }

                foreach ($rows['modified_fields'] as $key => $value) {
                    if (! array_key_exists($key, $rows['current_fields']) && in_array('CREATE', $value['modify_type'])) {
                        $added_field++;
                    }

                    if (in_array('RENAME', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_TYPE', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_LENGTH', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_NULL', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_ATTRIBUTE', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_INCREMENT', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_INDEX', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_DEFAULT', $value['modify_type'])) {
                        $altered_field++;
                    }
                }

                if ($dropped_fk > 0) {
                    $fk_fields = [];
                    foreach ($fk as $key => $value) {
                        $fk_fields[] = sprintf(self::DROP_FOREIGN_KEY, "'".$value['source_field']."'");
                    }
                    $dropped_fk_field = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fk_fields));
                }

                if ($altered_field > 0) {
                    $fields = self::getAlterMigrationUpFields($rows, $name);
                    /**
                     * Positioning schema in migration app
                     * 1. Indexes (Dropping / Adding)
                     * 2. Changes
                     * 3. Renaming.
                     */
                    if (isset($fields['indexes']) && count($fields['indexes']) > 0) {
                        $alter[] = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['indexes']));
                    }

                    if (isset($fields['change']) && count($fields['change']) > 0) {
                        $alter[] = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));
                    }

                    if (isset($fields['rename']) && count($fields['rename']) > 0) {
                        $alter[] = sprintf(self::RENAME_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
                    }

                    if (count($alter) > 1) {
                        $stub = implode(PHP_EOL.PHP_EOL, $alter);
                    } elseif (count($alter) > 0) {
                        $stub = $alter[0];
                    }
                }

                if ($dropped_field > 0) {
                    foreach ($rows['modified_fields'] as $key => $value) {
                        // if (in_array('UPDATE_INDEX', $value['modify_type'])) {
                        //     $modified[] = sprintf(
                        //         self::DROP_INDEX,
                        //         ucfirst($value['field_index']),
                        //         $value['field_name'],
                        //     );
                        // }

                        if (in_array('DROP_FIELD', $value['modify_type'])) {
                            $modified[] = sprintf(
                                self::DROP_FIELD,
                                $value['field_name']
                            );
                        }
                    }
                }

                if ($added_field > 0) {
                    foreach ($rows['modified_fields'] as $key => $value) {
                        if (in_array('CREATE', $value['modify_type'])) {
                            $modified[] = sprintf(
                                self::FIELD_STUB,
                                self::getMigrationTypeField($value['field_type']),
                                $value['field_name'],
                                self::getMigrationLengthField($value['field_length'], $value['field_type']),
                                self::getMigrationDefaultField($value['field_type'], $value['field_default']),
                                self::getMigrationNullField($value['field_null']),
                                self::getMigrationIndexField($value['field_index'], null, $value['field_name']),
                                self::getMigrationAttributeField($value['field_attribute']),
                                self::getMigrationIncrementField($value['field_increment'])
                            );
                        }
                    }
                }

                if ($dropped_field > 0 || $added_field > 0) {
                    $stub = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $modified)).PHP_EOL.PHP_EOL.$stub;
                }
            }
        }

        return ($dropped_fk_field ? $dropped_fk_field.PHP_EOL.PHP_EOL : null).$stub;
    }

    public static function getAlterMigrationSchemaDown($name, $rows = null, $prefix = null, $relations = [])
    {
        $stub = '';
        $dropped_fk_field = '';
        if ($prefix == 'rename') {
            $stub .= chr(9).chr(9).chr(9).sprintf(
                self::RENAME_TABLE_WRAPPER,
                $name['modified_name'],
                $name['current_name'],
            );
        } else {
            if (isset($rows) && count($rows) > 0) {
                $dropped_field = 0;
                $altered_field = 0;
                $added_field = 0;
                $dropped_fk = 0;
                $fields = [];
                $modified = [];
                $alter = [];
                $fk = [];

                if (! empty($relations)) {
                    foreach ($relations['modified_relations'] as $key => $relation) {
                        if (array_key_exists('modify_type', $relation) && $relation['modify_type'] === 'DROP_FOREIGN_KEY') {
                            $dropped_fk++;
                            $fk[] = $relation;
                        }
                    }
                }

                $rows = self::formatRows($rows);

                foreach ($rows['current_fields'] as $key => $value) {
                    if (! array_key_exists($key, $rows['modified_fields'])) {
                        $dropped_field++;
                        $rows['modified_fields'][$key] = $value;
                        $rows['modified_fields'][$key]['modify_type'] = ['DROP_FIELD'];
                    }
                }

                foreach ($rows['modified_fields'] as $key => $value) {
                    if (! array_key_exists($key, $rows['current_fields']) && in_array('CREATE', $value['modify_type'])) {
                        $added_field++;
                    }

                    if (in_array('RENAME', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_TYPE', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_LENGTH', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_NULL', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_ATTRIBUTE', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_INCREMENT', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_INDEX', $value['modify_type'])) {
                        $altered_field++;
                    }

                    if (in_array('UPDATE_DEFAULT', $value['modify_type'])) {
                        $altered_field++;
                    }
                }

                if ($dropped_fk > 0) {
                    $fk_fields = [];

                    foreach ($fk as $key => $value) {
                        $temp_field = sprintf(self::ADD_FOREIGN_KEY, $value['source_field'], $value['target_field'], $value['target_table']);
                        if (! empty($value['on_delete'])) {
                            $temp_field .= sprintf(self::ADD_FOREIGN_KEY_ON_DELETE, $value['on_delete']);
                        }
                        if (! empty($value['on_update'])) {
                            $temp_field .= sprintf(self::ADD_FOREIGN_KEY_ON_UPDATE, $value['on_update']);
                        }
                        $fk_fields[] = $temp_field.';';
                    }

                    $dropped_fk_field = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fk_fields));
                }

                if ($altered_field > 0) {
                    $fields = self::getAlterMigrationDownFields($rows, $name);

                    /**
                     * Positioning schema in migration app
                     * 1. Indexes (Dropping / Adding)
                     * 2. Changes
                     * 3. Renaming.
                     */
                    if (isset($fields['indexes']) && count($fields['indexes']) > 0) {
                        $alter[] = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['indexes']));
                    }

                    if (isset($fields['change']) && count($fields['change']) > 0) {
                        $alter[] = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));
                    }

                    if (isset($fields['rename']) && count($fields['rename']) > 0) {
                        $alter[] = sprintf(self::RENAME_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
                    }

                    if (count($alter) > 1) {
                        $stub = implode(PHP_EOL.PHP_EOL, $alter);
                    } elseif (count($alter) > 0) {
                        $stub = $alter[0];
                    }
                }

                if ($dropped_field > 0) {
                    foreach ($rows['modified_fields'] as $key => $value) {
                        if (in_array('DROP_FIELD', $value['modify_type'])) {
                            if (in_array($value['field_type'], ['timestamp']) && in_array($value['field_name'], ['deleted_at'])) {
                                $modified[] = sprintf(self::SOFT_DELETE);
                            } else {
                                $modified[] = sprintf(
                                    self::FIELD_STUB,
                                    self::getMigrationTypeField($value['field_type']),
                                    $value['field_name'],
                                    self::getMigrationLengthField($value['field_length'], $value['field_type']),
                                    $value['field_default'],
                                    self::getMigrationNullField($value['field_null']),
                                    self::getMigrationIndexField($value['field_index'], null, $value['field_name']),
                                    self::getMigrationAttributeField($value['field_attribute'] ?? null),
                                    self::getMigrationIncrementField($value['field_increment'])
                                );
                            }
                        }
                    }
                }

                if ($added_field > 0) {
                    foreach ($rows['modified_fields'] as $key => $value) {
                        if (in_array('CREATE', $value['modify_type'])) {
                            $modified[] = sprintf(
                                self::DROP_FIELD,
                                $value['field_name']
                            );
                        }
                    }
                }

                if ($dropped_field > 0 || $added_field > 0) {
                    $stub = sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $modified)).PHP_EOL.PHP_EOL.$stub;
                }
            }
        }

        return $stub.($dropped_fk_field ? $dropped_fk_field.PHP_EOL.PHP_EOL : null);
    }

    public static function getMigrationRelationshipUp($name, $relations)
    {
        $fields = [];

        foreach ($relations as $relation) {
            $field = sprintf(self::ADD_FOREIGN_KEY, $relation['source_field'], $relation['target_field'], $relation['target_table']);

            if (! empty($relation['on_delete'])) {
                $field .= sprintf(self::ADD_FOREIGN_KEY_ON_DELETE, $relation['on_delete']);
            }

            if (! empty($relation['on_update'])) {
                $field .= sprintf(self::ADD_FOREIGN_KEY_ON_UPDATE, $relation['on_update']);
            }

            $fields[] = $field.';';
        }

        return $fields;
    }

    public static function getAlterMigrationRelationshipUp($relations)
    {
        $fields = [];

        foreach ($relations['modified_relations'] as $relation) {
            $type = $relation['modify_type'] ?? null;
            if (! empty($type)) {
                if ($type === 'ADD_FOREIGN_KEY') {
                    $field = sprintf(self::ADD_FOREIGN_KEY, $relation['source_field'], $relation['target_field'], $relation['target_table']);

                    if (! empty($relation['on_delete'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_DELETE, $relation['on_delete']);
                    }

                    if (! empty($relation['on_update'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_UPDATE, $relation['on_update']);
                    }

                    $fields[] = $field.';';
                }

                if ($type === 'CHANGE_FOREIGN_KEY') {
                    $filteredRelation = "'".$relation['source_field']."'";
                    $fields[] = sprintf(self::DROP_FOREIGN_KEY, $filteredRelation);

                    $field = sprintf(self::ADD_FOREIGN_KEY, $relation['source_field'], $relation['target_field'], $relation['target_table']);
                    if (! empty($relation['on_delete'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_DELETE, $relation['on_delete']);
                    }
                    if (! empty($relation['on_update'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_UPDATE, $relation['on_update']);
                    }
                    $fields[] = $field.';';
                }
            }
        }

        return $fields;
    }

    public static function getMigrationRelationshipDown($relations)
    {
        $filteredRelation = collect(array_values($relations))->pluck('source_field')->toArray();
        $relation = "'".implode("', '", $filteredRelation)."'";

        return sprintf(self::DROP_FOREIGN_KEY, $relation);
    }

    public static function getAlterMigrationRelationshipDown($relations)
    {
        $fields = [];

        foreach ($relations['modified_relations'] as $key => $relation) {
            $type = $relation['modify_type'] ?? null;
            if (! empty($type)) {
                if ($type === 'ADD_FOREIGN_KEY') {
                    $filteredRelation = "'".$relation['source_field']."'";
                    $fields[] = sprintf(self::DROP_FOREIGN_KEY, $filteredRelation);
                }

                if ($type === 'CHANGE_FOREIGN_KEY') {
                    $filteredRelation = "'".$relation['source_field']."'";
                    $fields[] = sprintf(self::DROP_FOREIGN_KEY, $filteredRelation);

                    $field = sprintf(self::ADD_FOREIGN_KEY, $relation['source_field'], $relations['current_relations'][$key]['target_field'], $relations['current_relations'][$key]['target_table']);
                    if (! empty($relations['current_relations'][$key]['on_delete'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_DELETE, $relations['current_relations'][$key]['on_delete']);
                    }
                    if (! empty($relations['current_relations'][$key]['on_update'])) {
                        $field .= sprintf(self::ADD_FOREIGN_KEY_ON_UPDATE, $relations['current_relations'][$key]['on_update']);
                    }
                    $fields[] = $field.';';
                }
            }
        }

        return $fields;
    }

    public static function getMigrationFields($name, $rows)
    {
        $fields = [];

        foreach ($rows as $row) {
            if (in_array($row['field_type'], ['timestamp']) && in_array($row['field_name'], ['created_at', 'updated_at'])) {
                $fields[] = sprintf(self::TIMESTAMP);
            } elseif (in_array($row['field_type'], ['timestamp']) && in_array($row['field_name'], ['deleted_at'])) {
                $fields[] = sprintf(self::SOFT_DELETE);
            } else {
                if (! empty($row['field_index']) && $row['field_index'] !== 'foreign') {
                    $index = '->'.self::getMigrationIndexField($row['field_index'], null, $row['field_name']);
                } else {
                    $index = null;
                }

                if ($row['field_increment']) {
                    $index = null;
                }

                $fields[] = sprintf(
                    self::FIELD_STUB,
                    self::getMigrationTypeField($row['field_type']),
                    $row['field_name'],
                    self::getMigrationLengthField($row['field_length'], $row['field_type']),
                    self::getMigrationDefaultField($row['field_type'], $row['field_default']),
                    self::getMigrationNullField($row['field_null']),
                    $index,
                    self::getMigrationAttributeField($row['field_attribute'] ?? null, null),
                    self::getMigrationIncrementField($row['field_increment'])
                );
            }
        }
        $fields = array_unique($fields);

        return $fields;
    }

    public static function getAlterMigrationUpFields(array $rows, $table = null)
    {
        $stub = [];
        $rename = [];
        $indexes = [];

        foreach ($rows['modified_fields'] as $index => $row) {
            $current_fields = $rows['current_fields'][$index];
            if (in_array('RENAME', $row['modify_type'])) {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $current_fields['field_name'],
                    $row['field_name'],
                );
            }

            if (! empty(array_intersect($row['modify_type'], ['UPDATE_TYPE', 'UPDATE_LENGTH']))) {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN_LENGTH,
                    self::getMigrationTypeField($row['field_type']),
                    $current_fields['field_name'],
                    self::getMigrationLengthField($row['field_length'], $row['field_type'])
                );
            }

            if (in_array('UPDATE_NULL', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type'] ?? $current_fields['field_type']),
                    $current_fields['field_name'],
                    self::getMigrationLengthField($row['field_length'] ?? $current_fields['field_length'], $row['field_type']),
                    self::getMigrationNullField($row['field_null'], $current_fields['field_null'])
                );
            }

            if (in_array('UPDATE_ATTRIBUTE', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type'] ?? $current_fields['field_type']),
                    $current_fields['field_name'],
                    self::getMigrationLengthField($row['field_length'] ?? $current_fields['field_length'], $row['field_type']),
                    self::getMigrationAttributeField($row['field_attribute'] ?? null, $current_fields['field_attribute'])
                );
            }

            if (in_array('UPDATE_INCREMENT', $row['modify_type'])) {
                // DROP AUTO INCREMENT
                if ($row['field_increment'] == false && $current_fields['field_increment'] == true) {
                    $stub[] = sprintf(
                        self::DROP_PRIMARY_KEY,
                        $current_fields['field_name'],
                    );

                    $stub[] = sprintf(
                        self::DROP_AI,
                        self::getMigrationTypeField($row['field_type'] ?? $current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($row['field_length'] ?? $current_fields['field_length'], $row['field_type']),
                        self::getMigrationAttributeField($row['field_attribute'] ?? null, $current_fields['field_attribute']),
                    );
                }

                // ADD AUTO INCREMENT
                if ($row['field_increment'] == true && $current_fields['field_increment'] == false) {
                    $indexes[] = sprintf(
                        self::ADD_PRIMARY_KEY,
                        $current_fields['field_name'],
                    );

                    $stub[] = sprintf(
                        self::ADD_AI,
                        self::getMigrationTypeField($row['field_type'] ?? $current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($row['field_length'] ?? $current_fields['field_length'], $row['field_type']),
                        self::getMigrationIncrementField($row['field_increment'] ?? $current_fields['field_increment']),
                        self::getMigrationAttributeField($row['field_attribute'] ?? null, $current_fields['field_attribute']),
                    );
                }
            }

            if (! empty(array_intersect($row['modify_type'], ['UPDATE_INDEX', 'UPDATE_INCREMENT']))) {
                if ($current_fields['field_index'] === null && $row['field_index'] !== null) {
                    $stub[] = sprintf(
                        self::CREATE_INDEX,
                        self::getMigrationIndexField($row['field_index'], $current_fields['field_index'], $row['field_name']),
                    );
                } elseif ($current_fields['field_index'] !== null && $row['field_index'] === null) {
                    if ($current_fields['field_index'] !== 'primary' && $current_fields['field_index'] !== 'foreign') {
                        $stub[] = sprintf(
                            self::DROP_INDEX,
                            ucfirst($current_fields['field_index']),
                            $row['field_name']
                        );
                    }

                    if ($current_fields['field_index'] === 'foreign') {
                        $stub[] = sprintf(
                            self::DROP_INDEX,
                            'Index',
                            $table['current_name'].'_'.$row['field_name'].'_foreign'
                        );
                    }
                }
            } elseif (in_array('UPDATE_INDEX', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::CREATE_INDEX,
                    self::getMigrationIndexField($row['field_index'], $row['field_index'], $row['field_name']),
                );
            }

            if (in_array('UPDATE_DEFAULT', $row['modify_type'])) {
                if ($row['field_default'] === null && $current_fields['field_default'] !== null) {
                    $stub[] = sprintf(
                        self::DROP_DEFAULT,
                        $table['current_name'],
                        $current_fields['field_name'],
                    );
                } else {
                    $stub[] = sprintf(
                        self::CHANGE_COLUMN,
                        self::getMigrationTypeField($row['field_type'] ?? $current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($row['field_length'] ?? $current_fields['field_length'], $row['field_type']),
                        self::getMigrationDefaultField($row['field_type'], $row['field_default']),
                    );
                }
            }
        }

        $stub = array_unique($stub);
        $rename = array_unique($rename);
        $indexes = array_unique($indexes);

        return ['change' => $stub, 'rename' => $rename, 'indexes' => $indexes];
    }

    public static function getAlterMigrationDownFields(array $rows, $table = null)
    {
        $stub = [];
        $rename = [];
        $indexes = [];

        foreach ($rows['modified_fields'] as $index => $row) {
            $current_fields = $rows['current_fields'][$index];
            if (in_array('RENAME', $row['modify_type'])) {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $row['field_name'],
                    $current_fields['field_name'],
                );
            }

            if (! empty(array_intersect($row['modify_type'], ['UPDATE_TYPE', 'UPDATE_LENGTH']))) {
                $stub[] = sprintf(
                    self::CHANGE_FIELD_STUB,
                    self::getMigrationTypeField($current_fields['field_type']),
                    $row['field_name'],
                    self::getMigrationLengthField($current_fields['field_length'], $current_fields['field_type']),
                    null,
                    null,
                    null,
                    null,
                    null
                );
            }

            if (in_array('UPDATE_NULL', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($current_fields['field_type']),
                    $current_fields['field_name'],
                    self::getMigrationLengthField($current_fields['field_length'], $current_fields['field_type']),
                    self::getMigrationNullField($current_fields['field_null'], $row['field_null'])
                );
            }

            if (in_array('UPDATE_ATTRIBUTE', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($current_fields['field_type']),
                    $current_fields['field_name'],
                    self::getMigrationLengthField($current_fields['field_length'], $current_fields['field_type']),
                    self::getMigrationAttributeField($current_fields['field_attribute'], $row['field_attribute'])
                );
            }

            if (in_array('UPDATE_INCREMENT', $row['modify_type'])) {
                if ($row['field_increment'] == true && $current_fields['field_increment'] == false) {
                    $stub[] = sprintf(
                        self::DROP_PRIMARY_KEY,
                        $row['field_name'],
                    );

                    $stub[] = sprintf(
                        self::DROP_AI,
                        self::getMigrationTypeField($current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($current_fields['field_length'], $current_fields['field_type']),
                        self::getMigrationAttributeField($current_fields['field_attribute'], $row['field_attribute'])
                    );
                }

                if ($row['field_increment'] == false && $current_fields['field_increment'] == true) {
                    $indexes[] = sprintf(
                        self::ADD_PRIMARY_KEY,
                        $row['field_name'],
                    );

                    $stub[] = sprintf(
                        self::ADD_AI,
                        self::getMigrationTypeField($current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($current_fields['field_length'], $current_fields['field_type']),
                        self::getMigrationIncrementField($current_fields['field_increment']),
                        self::getMigrationAttributeField($current_fields['field_attribute'], $row['field_attribute'])
                    );
                }
            }

            if (! empty(array_intersect($row['modify_type'], ['UPDATE_INDEX', 'UPDATE_INCREMENT']))) {
                if ($current_fields['field_index'] !== null && $row['field_index'] === null) {
                    if ($current_fields['field_index'] !== 'primary' && $current_fields['field_index'] !== 'foreign') {
                        $indexes[] = sprintf(
                            self::CREATE_INDEX,
                            self::getMigrationIndexField($row['field_index'], $current_fields['field_index'], $row['field_name']),
                        );
                    }
                } elseif ($current_fields['field_index'] === null && $row['field_index'] !== null && $row['field_index'] === 'primary') {
                    $indexes[] = sprintf(
                        self::DROP_INDEX,
                        ucfirst($row['field_index']),
                        $table['current_name'],
                    );
                }
            } elseif (in_array('UPDATE_INDEX', $row['modify_type'])) {
                $stub[] = sprintf(
                    self::DROP_INDEX_TABLE,
                    ucfirst($row['field_index']),
                    $table['current_name'],
                    $row['field_name'],
                    $row['field_index']
                );
            }

            if (in_array('UPDATE_DEFAULT', $row['modify_type'])) {
                if ($row['field_default'] === null && $current_fields['field_default'] !== null) {
                    $stub[] = sprintf(
                        self::CHANGE_COLUMN,
                        self::getMigrationTypeField($current_fields['field_type'] ?? $current_fields['field_type']),
                        $current_fields['field_name'],
                        self::getMigrationLengthField($current_fields['field_length'] ?? $current_fields['field_length'], $current_fields['field_type']),
                        self::getMigrationDefaultField($current_fields['field_type'], $current_fields['field_default']),
                    );
                } else {
                    $stub[] = sprintf(
                        self::DROP_DEFAULT,
                        $table['current_name'],
                        $current_fields['field_name'],
                    );
                }
            }
        }

        $stub = array_unique($stub);
        $rename = array_unique($rename);
        $indexes = array_unique($indexes);

        return ['change' => $stub, 'rename' => $rename, 'indexes' => $indexes];
    }

    public static function getMigrationTypeField($fieldType)
    {
        $type = $fieldType;

        switch ($fieldType) {
            case 'varchar':
                $type = 'string';
                break;
            case 'character varying':
                $type = 'string';
                break;
            case 'char':
                break;
            case 'tinyint':
                $type = 'tinyInteger';
                break;
            case 'smallint':
                $type = 'smallInteger';
                break;
            case 'mediumint':
                $type = 'mediumInteger';
                break;
            case 'bigint':
                $type = 'bigInteger';
                break;
            case 'datetime':
                $type = 'dateTime';
                break;
            case 'mediumtext':
                $type = 'mediumText';
                break;
            case 'longtext':
                $type = 'longText';
                break;
            case 'blob':
                $type = 'binary';
                break;
            case 'multipoint':
                $type = 'multiPoint';
                break;
            case 'multipolygon':
                $type = 'multiPolygon';
                break;
            case 'multilinestring':
                $type = 'multiLineString';
                break;
            case 'geometrycollection':
                $type = 'geometryCollection';
                break;
        }

        return $type;
    }

    public static function getMigrationLengthField($field, $fieldType)
    {
        if (in_array($fieldType, ['enum', 'set'])) {
            $explodedItem = explode(',', $field);
            $result = '';
            foreach ($explodedItem as $index => $item) {
                if ($index == count($explodedItem) - 1) {
                    $result = $result.sprintf(self::FIELD_ARRAY_LENGTH_CONTENT_LAST, $item);
                } else {
                    $result = $result.sprintf(self::FIELD_ARRAY_LENGTH_CONTENT, $item);
                }
            }

            return sprintf(self::FIELD_ARRAY_LENGTH, $result);
        } elseif (in_array($fieldType, ['varchar', 'char'])) {
            return sprintf(self::FIELD_DECIMAL_LENGTH, $field);
        } elseif (in_array($fieldType, [null, 'null', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint'])) {
            return;
        } elseif (in_array($fieldType, ['double', 'float', 'decimal'])) {
            return sprintf(self::FIELD_DECIMAL_LENGTH, $field);
        }
    }

    public static function getMigrationDefaultField($fieldType, $field)
    {
        if (! empty($field)) {
            if (in_array($fieldType, ['integer', 'float', 'double', 'decimal'])) {
                return sprintf(self::FIELD_DEFAULT_DECIMAL, $field);
            } else {
                return sprintf(self::FIELD_DEFAULT, $field);
            }
        }
    }

    public static function getMigrationNullField($field, $oldField = null)
    {
        if (isset($oldField)) {
            if ($field === true && $oldField === false) {
                return sprintf(self::FIELD_NULLABLE, 'true');
            }

            if ($field === false && $oldField === true) {
                return sprintf(self::FIELD_NULLABLE, 'false');
            }
        } else {
            if ($field === true) {
                return sprintf(self::FIELD_NULLABLE, 'true');
            }
        }
    }

    public static function getMigrationIndexField($field, $current = null, $name = null)
    {
        if ($field !== 'foreign') {
            if ($field === null && $current !== null) {
                return sprintf(self::FIELD_INDEX, $current, $name);
            } elseif ($field !== null && $current === null) {
                return sprintf(self::FIELD_INDEX, $field, $name);
            }
        }
    }

    public static function getMigrationIncrementField($field)
    {
        if ($field === true) {
            return sprintf(self::FIELD_INCREMENT);
        }
    }

    public static function getMigrationAttributeField($field, $current = null)
    {
        if ($current === false && $field === true) {
            return sprintf(self::FIELD_ATTRIBUTE, 'unsigned');
        } elseif ($current === true && $field === false) {
            return sprintf(self::REMOVE_UNSIGNED);
        } elseif ($field === true) {
            return sprintf(self::FIELD_ATTRIBUTE, 'unsigned');
        } else {
            return;
        }
    }

    public static function convertPascalToSnake(string $input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    public static function getRandomCharacter($length)
    {
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($permitted_chars), 0, $length);
    }

    public static function formatRows($rows)
    {
        $current_fields = $rows['current_fields'];
        $modified_fields = $rows['modified_fields'];

        foreach ($current_fields as $key => $value) {
            $altered_current_fields[$value['id']] = $value;
            unset($altered_current_fields[$value['id']]['id']);
        }

        foreach ($modified_fields as $key => $value) {
            $altered_modified_fields[$value['id']] = $value;
            unset($altered_modified_fields[$value['id']]['id']);
        }

        return ['current_fields' => $altered_current_fields, 'modified_fields' => $altered_modified_fields];
    }
}
