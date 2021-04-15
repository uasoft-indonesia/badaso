<?php

namespace Uasoft\Badaso\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Carbon\Carbon;

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
    ->%s()
    TXT;

    const FIELD_ATTRIBUTE = <<<'TXT'
    ->%s()
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
    $table->%s('%s');
    TXT;

    const TIMESTAMP = <<<'TXT'
    $table->timestamps();
    TXT;

    public static function getMigrationSchemaUp($name, $rows, $prefix = null, bool $timestamp = true) {
        if ($prefix == 'drop') {
            return sprintf(
                self::MIGRATION_DOWN_WRAPPER,
                $name,
            );
        }

        return sprintf(self::MIGRATION_UP_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), self::getMigrationFields($name, $rows, $timestamp)));
    }

    public static function getMigrationSchemaDown($name, $rows = [], $prefix = null, bool $timestamp = true) {
        if ($prefix == 'drop') {
            return sprintf(self::MIGRATION_UP_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), self::getMigrationFields($name, $rows, $timestamp)));
        }

        return sprintf(
            self::MIGRATION_DOWN_WRAPPER,
            $name,
        );
    }

    public static function getAlterMigrationSchemaUp($name, $rows, $prefix = null) {
        $stub = '';
        if ($prefix == 'rename') {
            $stub .= sprintf(
                self::RENAME_TABLE_WRAPPER, 
                $name['current_name'],
                $name['modified_name'],
            );
        } else {
            if (isset($rows) && count($rows) > 0) {
                /**
                 * Here we separate modify type of fields
                 */
                $dropped_field = 0;
                $altered_field = 0;
                $added_field = 0;

                foreach ($rows as $key => $value) {
                    if ($value['modify_type'] === 'DROP_FIELD') {
                        $dropped_field++;
                    } elseif ($value['modify_type'] !== "CREATE") {
                        $altered_field++;
                    } elseif ($value['modify_type'] === "CREATE") {
                        $added_field++;
                    }
                }

                if ($altered_field > 0) {
                    $fields = self::getAlterMigrationUpFields($rows);
                    /**
                     * Positioning schema in migration app
                     * 1. Indexes (Dropping / Adding)
                     * 2. Changes
                     * 3. Renaming
                     */

                    if (isset($fields['indexes']) && count($fields['indexes']) > 0 ) {
                        $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['indexes'])).PHP_EOL.PHP_EOL;
                    }

                    $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));
        
                    if (isset($fields['rename']) && count($fields['rename']) > 0 ) {
                        $stub .= PHP_EOL.PHP_EOL. sprintf(self::RENAME_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
                    }
                }

                if ($dropped_field > 0) {
                    foreach ($rows as $key => $value) {
                        if ($value['modify_type'] == 'UPDATE_INDEX') {
                            $fields[] = sprintf(
                                self::DROP_INDEX,
                                ucfirst($value['field']['field_index']),
                                $value['field']['field_name'],
                            );
                        }

                        if ($value['modify_type'] == 'DROP_FIELD') {
                            $fields[] = sprintf(
                                self::DROP_FIELD,
                                $value['field']['field_name']
                            );
                        }
                    }
                }

                if ($added_field > 0) {
                    foreach ($rows as $key => $value) {
                        if ($value['modify_type'] == 'CREATE') {
                            $fields[] = sprintf(
                                self::FIELD_STUB,
                                self::getMigrationTypeField($value['field_type']),
                                $value['field_name'],
                                self::getMigrationLengthField($value['field_length'], $value['field_type']),
                                self::getMigrationDefaultField($value['field_type'], $value['field_default'], $value['as_defined']),
                                self::getMigrationNullField($value['field_null']),
                                self::getMigrationIndexField($value['field_index'], $value['field_name'], $value['field_increment']),
                                self::getMigrationAttributeField($value['field_attribute']),
                                self::getMigrationIncrementField($value['field_increment'])
                            );
                        }
                    }
                }

                if ($dropped_field > 0 && $added_field > 0) {
                    $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields));
                }
            }
        }

        return $stub;
    }

    public static function getAlterMigrationSchemaDown($name, $rows = null, $prefix = null) {
        $stub = '';
        if ($prefix == 'rename') {
            $stub .= sprintf(
                self::RENAME_TABLE_WRAPPER, 
                $name['modified_name'],
                $name['current_name'],
            );
        } else {
            if (isset($rows) && count($rows) > 0) {
                $dropped_field = 0;
                $altered_field = 0;
                $added_field = 0;

                foreach ($rows as $key => $value) {
                    if ($value['modify_type'] === 'DROP_FIELD') {
                        $dropped_field++;
                    } elseif ($value['modify_type'] !== "CREATE") {
                        $altered_field++;
                    } elseif ($value['modify_type'] === "CREATE") {
                        $added_field++;
                    }
                }

                if ($altered_field > 0) {
                    $fields = self::getAlterMigrationDownFields($rows, $name);

                    /**
                     * Positioning schema in migration app
                     * 1. Indexes (Dropping / Adding)
                     * 2. Changes
                     * 3. Renaming
                     */

                    if (isset($fields['indexes']) && count($fields['indexes']) > 0 ) {
                        $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['indexes'])).PHP_EOL.PHP_EOL;
                    }

                    $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));
        
                    if (isset($fields['rename']) && count($fields['rename']) > 0 ) {
                        $stub .= PHP_EOL.PHP_EOL. sprintf(self::RENAME_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
                    }
                }

                if ($dropped_field > 0) {
                    foreach ($rows as $key => $value) {
                        if ($value['modify_type'] == 'UPDATE_INDEX') {
                            $fields[] = sprintf(
                                self::CREATE_INDEX,
                                $value['field']['field_index'],
                                $value['field']['field_name'],
                            );
                        }

                        if ($value['modify_type'] == 'DROP_FIELD') {
                            $fields[] = sprintf(
                                self::FIELD_STUB,
                                self::getMigrationTypeField($value['field']['field_type']),
                                $value['field']['field_name'],
                                self::getMigrationLengthField($value['field']['field_length'], $value['field']['field_type']),
                                self::getMigrationDefaultField($value['field']['field_type'], $value['field']['field_default'], $value['field']['as_defined']),
                                self::getMigrationNullField($value['field']['field_null']),
                                self::getMigrationIndexField($value['field']['field_index'], $value['field']['field_name'], $value['field']['field_increment']),
                                self::getMigrationAttributeField($value['field']['field_attribute']),
                                self::getMigrationIncrementField($value['field']['field_increment'])
                            );
                        }
                    }
                }

                if ($added_field > 0) {
                    foreach ($rows as $key => $value) {
                        if ($value['modify_type'] == 'CREATE') {
                            $fields[] = sprintf(
                                self::DROP_FIELD,
                                $value['field_name']
                            );
                        }
                    }
                }

                if ($dropped_field > 0 && $added_field > 0) {
                    $stub .= sprintf(self::ALTER_WRAPPER, $name['current_name'], implode(PHP_EOL.chr(9).chr(9).chr(9), $fields));
                }
            }
        }

        return $stub;
    }

    public static function getMigrationFields($name, $rows, $timestamp)
    {
        $fields = [];

        foreach ($rows as $row) {
            if (!in_array($row['field_name'], ['updated_at', 'created_at']) && !in_array($row['field_type'], ['timestamp'])) {
                $fields[] = sprintf(
                    self::FIELD_STUB,
                    self::getMigrationTypeField($row['field_type']),
                    $row['field_name'],
                    self::getMigrationLengthField($row['field_length'], $row['field_type']),
                    self::getMigrationDefaultField($row['field_type'], $row['field_default'], $row['as_defined']),
                    self::getMigrationNullField($row['field_null']),
                    self::getMigrationIndexField($row['field_index'], $row['field_name'], $row['field_increment']),
                    self::getMigrationAttributeField($row['field_attribute']),
                    self::getMigrationIncrementField($row['field_increment'])
                );
            }
        }

        if ($timestamp == true) {
            $fields[] = sprintf(
                self::TIMESTAMP
            );
        }

        return $fields;
    }

    public static function getAlterMigrationUpFields(array $rows)
    {
        $stub = [];
        $rename = [];
        $indexes = [];

        foreach ($rows as $index => $row) {
            if ($row['modify_type'] == 'RENAME') {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $row['current'],
                    $row['new'],
                );
            } elseif (in_array($row['modify_type'], ['UPDATE_LENGTH', 'UPDATE_TYPE']))  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN_LENGTH,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['current'],
                    self::getMigrationLengthField($row['field_length']['new'] ?? $row['field_length']['current'], $row['field_type']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_DEFAULT')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new'] ?? $row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationDefaultField($row['field_type'], $row['field_default']['new'], $row['as_defined'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_NULL')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new'] ?? $row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationNullField($row['field_null']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INDEX')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new'] ?? $row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationIndexField($row['field_index']['new'], $row['field_name']['current'], $row['field_increment']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_ATTRIBUTE')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new'] ?? $row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationAttributeField($row['field_attribute']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INCREMENT')  {
                if ($row['field_increment']['new'] == true && $row['field_increment']['current'] == false) {
                    $indexes[] = sprintf(
                        self::DROP_PRIMARY_KEY,
                        $row['field_name']['current'],
                    );

                    $indexes[] = sprintf(
                        self::ADD_PRIMARY_KEY,
                        $row['field_name']['current'],
                    );
                }
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new'] ?? $row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationIncrementField($row['field_increment']['new'] ?? $row['field_increment']['current'])
                );
            } elseif ($row['modify_type'] == 'CREATE')  {
                $stub[] = sprintf(
                    self::FIELD_STUB,
                    self::getMigrationTypeField($row['field_type']),
                    $row['field_name'],
                    self::getMigrationLengthField($row['field_length'], $row['field_type']),
                    self::getMigrationDefaultField($row['field_type'], $row['field_default'], $row['as_defined']),
                    self::getMigrationNullField($row['field_null']),
                    self::getMigrationIndexField($row['field_index'], $row['field_name'], $row['field_increment']),
                    self::getMigrationAttributeField($row['field_attribute']),
                    self::getMigrationIncrementField($row['field_increment'])
                );
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

        foreach ($rows as $index => $row) {
            if ($row['modify_type'] == 'RENAME') {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $row['new'],
                    $row['current'],
                );
            } elseif ($row['modify_type'] == 'CREATE')  {
                $stub[] = sprintf(
                    self::DROP_FIELD,
                    $row['field_name'],
                );
            } else {
                if ($row['modify_type'] == 'UPDATE_INDEX') {
                    $stub[] = sprintf(
                        self::DROP_INDEX,
                        ucfirst($row['field_index']['new']),
                        $row['field_name']['new'],
                    );

                    $stub[] = sprintf(
                        self::DROP_INDEX_TABLE,
                        ucfirst($row['field_index']['new']),
                        $table['current_name'],
                        $row['field_name']['new'],
                        $row['field_index']['new']
                    );
                } else if ($row['modify_type'] == 'UPDATE_INCREMENT') {
                    if ($row['field_increment']['new'] == false && $row['field_increment']['current'] == true) {
                        $indexes[] = sprintf(
                            self::DROP_PRIMARY_KEY,
                            $row['field_name']['new'],
                        );

                        $indexes[] = sprintf(
                            self::ADD_PRIMARY_KEY,
                            $row['field_name']['new'],
                        );
                    }

                    $stub[] = sprintf(
                        self::CHANGE_COLUMN,
                        self::getMigrationTypeField($row['field_type']['current']),
                        $row['field_name']['new'],
                        null,
                        self::getMigrationIncrementField($row['field_increment']['current'] ?? $row['field_increment']['new'])
                    );
                } else {
                    $stub[] = sprintf(
                        self::CHANGE_FIELD_STUB,
                        self::getMigrationTypeField($row['field_type']['current']),
                        $row['field_name']['new'],
                        self::getMigrationLengthField($row['field_length']['current'], $row['field_type']['current']),
                        self::getMigrationDefaultField($row['field_type']['current'], $row['field_default']['current']),
                        self::getMigrationNullField($row['field_null']['current']),
                        self::getMigrationIndexField($row['field_index']['current'], $row['field_name']['current'], $row['field_increment']['current']),
                        self::getMigrationAttributeField($row['field_attribute']['current']),
                        self::getMigrationIncrementField($row['field_increment']['current'] ?? $row['field_increment']['new'])
                    );
                }
            } 
        }

        $stub = array_unique($stub);
        $rename = array_unique($rename);
        $indexes = array_unique($indexes);

        return ['change' => $stub, 'rename' => $rename, 'indexes' => $indexes];
    }

    public static function getMigrationTypeField($fieldType) {
        $type = $fieldType;

        switch ($fieldType) {
            case 'varchar':
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

    public static function getMigrationLengthField($field, $fieldType) {
        if (in_array( $fieldType, ['enum', 'set'])) {
            $explodedItem = explode(',', $field);
            $result = '';
            foreach ($explodedItem as $index => $item) {
                if ($index == count($explodedItem) - 1) {
                    $result = $result . sprintf(self::FIELD_ARRAY_LENGTH_CONTENT_LAST, $item);
                } else {
                    $result = $result . sprintf(self::FIELD_ARRAY_LENGTH_CONTENT, $item);
                }
            }
            return sprintf(self::FIELD_ARRAY_LENGTH, $result);
        } elseif (in_array($fieldType, ['varchar', 'char'])) {
            return sprintf(self::FIELD_DECIMAL_LENGTH, $field);
        } elseif (in_array($fieldType, [null, 'null', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint'])) {
            return;
        }
    }

    public static function getMigrationDefaultField($fieldType, $field, $asDefined = null) {
        if ($field !== null) {
            if (in_array($fieldType, ['integer', 'float', 'double', 'decimal'])) {
                return sprintf(self::FIELD_DEFAULT_DECIMAL, $field) ;
            } elseif (isset($field) && $field === 'current_timestamp') {
                return sprintf(self::FIELD_DEFAULT_CURRENT_TIMESTAMP);
            } elseif ($field === 'as_defined') {
                return sprintf(self::FIELD_DEFAULT, $asDefined['new'] ?? preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $asDefined));
            } elseif ($fieldType === 'timestamp') {
                return sprintf(self::FIELD_DEFAULT, 0);
            } else {
                return sprintf(self::FIELD_DEFAULT, $field);
            }
        } else {
            return sprintf(self::FIELD_DEFAULT_NULL);
        }
    }

    public static function getMigrationNullField($field) {
        return sprintf(self::FIELD_NULLABLE, $field === true ? 'true' : 'false');
    }

    public static function getMigrationIndexField($field, $name, $increment) {
        if ($field === null) {
            return;
        } elseif ($increment === true && $field === 'primary') {
            return;
        } else {
            return sprintf(self::FIELD_INDEX, $field);
        }
    }

    public static function getMigrationIncrementField($field) {
        if ($field === true) {
            return sprintf(self::FIELD_INCREMENT);
        }
        return;
    }

    public static function getMigrationAttributeField($field) {
        if ($field === null) {
            return;
        } else {
            return sprintf(self::FIELD_ATTRIBUTE, $field);
        }
    }

    public static function convertPascalToSnake(string $input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function getRandomCharacter($length) {
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
}
