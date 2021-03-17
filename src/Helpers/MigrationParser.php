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
    ->%s('%s')
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
    $table->%s('%s'%s)%s->change();
    TXT;

    const CHANGE_COLUMN_LENGTH = <<<'TXT'
    $table->%s('%s'%s)->change();
    TXT;

    const MIGRATION_DOWN_WRAPPER = <<<'TXT'
            Schema::dropIfExists('%s');
    TXT;

    public static function getMigrationFileName($name, $action)
    {
        return Carbon::now()->format('Y_m_d_his') . '_' . $action . '_table_' . $name;
    }

    public static function getMigrationSchemaUp($name, $rows) {
        $stub = '';

        if (isset($rows) && count($rows) > 0) {
            $fields = self::getAlterMigrationUpFields($rows);
            $stub = sprintf(self::ALTER_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));

            if (isset($fields['rename'])) {
                $stub .= PHP_EOL.PHP_EOL. sprintf(self::RENAME_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
            }
        } else {
            $stub .= sprintf(self::MIGRATION_UP_WRAPPER, $name, implode(PHP_EOL.chr(9), self::getMigrationFields($name, $rows)));
        }

        return $stub;
    }

    public static function getMigrationSchemaDown($name, $rows = null) {
        $stub = '';
        
        if (isset($rows) && count($rows) > 0) {
            $fields = self::getAlterMigrationDownFields($rows);
            $stub = sprintf(self::ALTER_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['change']));

            if (isset($fields['rename'])) {
                $stub .= PHP_EOL.PHP_EOL. sprintf(self::RENAME_WRAPPER, $name, implode(PHP_EOL.chr(9).chr(9).chr(9), $fields['rename']));
            }
        } else {
            $stub = sprintf(
                self::MIGRATION_DOWN_WRAPPER,
                $name,
            );
        }

        return $stub;
    }

    public static function getMigrationFields($name, $rows)
    {
        $fields = [];

        foreach ($rows as $row) {
            $fieldName = $row['field_name'];
            $fieldType = $row['field_type'];
            $fieldLength = null;
            $fieldDefault = null;
            $fieldNullable = false;
            $fieldIndex = null;
            $fieldAttribute = null;
            $fieldIncrement = false;

            if (isset($row['field_attribute'])) {
                if ($row['field_attribute'] != false) {
                    $fieldAttribute = sprintf(self::FIELD_ATTRIBUTE, $row['field_attribute']);
                }
            }

            if (isset($row['field_length'])) {
                if ($row['field_length'] != null) {
                    $fieldLength = sprintf(self::FIELD_DECIMAL_LENGTH, $row['field_length']);
                }
            }

            if (isset($row['field_default'])) {
                if ($row['field_default'] != null) {
                    $fieldDefault = sprintf(self::FIELD_DEFAULT, $row['field_default']);
                }
            }

            if (isset($row['field_null'])) {
                if ($row['field_null'] != false) {
                    $fieldNullable = sprintf(self::FIELD_NULLABLE, $row['field_null']);
                }
            }

            if (isset($row['field_index'])) {
                if ($row['field_index'] != null) {
                    $fieldIndex = sprintf(self::FIELD_INDEX, $row['field_index'], $row['field_name'])
                    ;
                }
            }

            if (in_array($fieldType, ['decimal', 'integer', 'float', 'double'])) {
                if ($fieldLength != null) {
                    $fieldLength = sprintf(self::FIELD_DECIMAL_LENGTH, $row['field_length']);
                    if (isset($row['field_default'])) {
                        if ($row['field_default'] != null) {
                            $fieldDefault = sprintf(self::FIELD_DEFAULT_DECIMAL, $row['field_default']);
                        }
                    }
                }
            }

            switch ($fieldType) {
                case 'int':
                    $fieldType = 'integer';
                    break;
                case 'varchar':
                    $fieldType = 'string';
                    $fieldLength = sprintf(self::FIELD_DECIMAL_LENGTH, $row['field_length']);
                    break;
                case 'char':
                    $fieldLength = sprintf(self::FIELD_DECIMAL_LENGTH, $row['field_length']);
                    break;
                case 'tinyint':
                    $fieldType = 'tinyInteger';
                    break;
                case 'smallint':
                    $fieldType = 'smallInteger';
                    break;
                case 'mediumint':
                    $fieldType = 'mediumInteger';
                    break;
                case 'bigint':
                    $fieldType = 'bigInteger';
                    break;
                case 'datetime':
                    $fieldType = 'dateTime';
                    break;
                case 'mediumtext':
                    $fieldType = 'mediumText';
                    break;
                case 'longtext':
                    $fieldType = 'longText';
                    break;
                case 'blob':
                    $fieldType = 'binary';
                    break;
                case 'multipoint':
                    $fieldType = 'multiPoint';
                    break;
                case 'multipolygon':
                    $fieldType = 'multiPolygon';
                    break;
                case 'multilinestring':
                    $fieldType = 'multiLineString';
                    break;
                case 'geometrycollection':
                    $fieldType = 'geometryCollection';
                    break;
                case 'enum':
                case 'set':
                    $explodedItem = explode(',', $row['field_length']);
                    $result = '';

                    foreach ($explodedItem as $index => $item) {
                        if ($index == count($explodedItem) - 1) {
                            $result = $result . sprintf(self::FIELD_ARRAY_LENGTH_CONTENT_LAST, $item);
                        } else {
                            $result = $result . sprintf(self::FIELD_ARRAY_LENGTH_CONTENT, $item);
                        }
                    }

                    $fieldLength = sprintf(self::FIELD_ARRAY_LENGTH, $result);
                    break;
            }

            if (isset($row['field_increment'])) {
                $fieldIncrement = sprintf(self::FIELD_INCREMENT);
                $fieldIndex = null;
            }

            $fields[] = chr(9).chr(9).sprintf(
                self::FIELD_STUB,
                $fieldType,
                $fieldName,
                $fieldLength,
                $fieldDefault,
                $fieldNullable,
                $fieldIndex,
                $fieldAttribute,
                $fieldIncrement
            );
        }

        return $fields;
    }

    public static function getAlterMigrationUpFields(array $rows)
    {
        $stub = [];
        $rename = [];

        foreach ($rows as $index => $row) {
            if ($row['modify_type'] == 'RENAME') {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $row['current'],
                    $row['new'],
                );
            } elseif ($row['modify_type'] == 'UPDATE_TYPE')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    self::getMigrationLengthField($row['field_length']['new']),
                    null
                );
            } elseif ($row['modify_type'] == 'UPDATE_LENGTH')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN_LENGTH,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    self::getMigrationLengthField($row['field_length']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_DEFAULT')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationDefaultField($row['field_type'], $row['field_default']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_NULL')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationNullField($row['field_null']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INDEX')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationIndexField($row['field_index']['new'], $row['field_name'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_ATTRIBUTE')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationAttributeField($row['field_attribute']['new'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INCREMENT')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['current']),
                    $row['field_name']['current'],
                    null,
                    self::getMigrationIncrementField($row['field_increment']['new'])
                );
            }
        }

        return ['change' => $stub, 'rename' => $rename];
    }

    public static function getAlterMigrationDownFields(array $rows)
    {
        $stub = [];
        $rename = [];

        foreach ($rows as $index => $row) {
            if ($row['modify_type'] == 'RENAME') {
                $rename[] = sprintf(
                    self::RENAME_COLUMN,
                    $row['new'],
                    $row['current'],
                );
            } elseif ($row['modify_type'] == 'UPDATE_TYPE')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    self::getMigrationLengthField($row['field_length']['current']),
                    null
                );
            } elseif ($row['modify_type'] == 'UPDATE_LENGTH')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN_LENGTH,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    self::getMigrationLengthField($row['field_length']['current'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_DEFAULT')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    null,
                    self::getMigrationDefaultField($row['field_type'], $row['field_default']['current'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_NULL')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    null,
                    self::getMigrationNullField($row['field_null']['current'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INDEX')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    null,
                    self::getMigrationIndexField($row['field_index']['current'], $row['field_name'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_ATTRIBUTE')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    null,
                    self::getMigrationAttributeField($row['field_attribute']['current'])
                );
            } elseif ($row['modify_type'] == 'UPDATE_INCREMENT')  {
                $stub[] = sprintf(
                    self::CHANGE_COLUMN,
                    self::getMigrationTypeField($row['field_type']['new']),
                    $row['field_name']['new'],
                    null,
                    self::getMigrationIncrementField($row['field_increment']['current'])
                );
            }
        }

        return ['change' => $stub, 'rename' => $rename];
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

    public static function getMigrationLengthField($field) {
        if (is_array($field)) {
            return sprintf(self::FIELD_ARRAY_LENGTH, $field);
        } elseif ($field == null || $field == 'null') {
            return;
        } else {
            return sprintf(self::FIELD_DECIMAL_LENGTH, $field);
        }
    }

    public static function getMigrationDefaultField($fieldType, $field) {
        if ($field !== null) {
            if ($fieldType == 'integer' || $fieldType == 'float' || $fieldType == 'double' || $fieldType == 'decimal') {
                return sprintf(self::FIELD_DEFAULT_DECIMAL, $field) ;
            } else {
                return sprintf(self::FIELD_DEFAULT, $field);
            }
        }
    }

    public static function getMigrationNullField($field) {
        return sprintf(self::FIELD_NULLABLE, $field === true ? 'true' : 'false');
    }

    public static function getMigrationIndexField($field, $name) {
        if ($field === null) {
            return;
        } else {
            return sprintf(self::FIELD_INDEX, $field, $name);
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
