<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Database\Eloquent\Model;

class CaseConvert
{
    public static function camel($dto)
    {
        if (is_string($dto)) {
            return self::convertString($dto);
        } elseif (is_array($dto)) {
            return self::convertArray($dto);
        } elseif (is_object($dto)) {
            return self::convertObject($dto);
        }

        return $dto;
    }

    public static function snake($dto)
    {
        if (is_string($dto)) {
            return self::convertString($dto, 'SNAKE');
        } elseif (is_array($dto)) {
            return self::convertArray($dto, 'SNAKE');
        } elseif (is_object($dto)) {
            return self::convertObject($dto, 'SNAKE');
        }

        return $dto;
    }

    public static function pascal($dto)
    {
        if (is_string($dto)) {
            return self::convertString($dto, 'PASCAL');
        } elseif (is_array($dto)) {
            return self::convertArray($dto, 'PASCAL');
        } elseif (is_object($dto)) {
            return self::convertObject($dto, 'PASCAL');
        }

        return $dto;
    }

    private static function convertString($param, $type = 'CAMEL')
    {
        $result = '';
        switch ($type) {
            case 'SNAKE':
                $strings = str_split($param, 1);
                foreach ($strings as $key => $value) {
                    if ($key > 0) {
                        if (ctype_upper($value)) {
                            $result = $result.'_'.strtolower($value);
                        } else {
                            $result = $result.''.$value;
                        }
                    } else {
                        $result = $result.''.strtolower($value);
                    }
                }
                break;
            case 'PASCAL':
                $strings = explode('_', $param);
                foreach ($strings as $key => $value) {
                    $result = $result.''.ucfirst($value);
                }
                break;
            default:
                $strings = explode('_', $param);
                foreach ($strings as $key => $value) {
                    if ($key > 0) {
                        $result = $result.''.ucfirst($value);
                    } else {
                        $result = $result.''.$value;
                    }
                }
        }

        return $result;
    }

    private static function convertArray($param, $type = 'CAMEL')
    {
        $objects = [];
        foreach ($param as $key => $object) {
            if (is_object($object)) {
                $objects[self::convertString($key, $type)] = self::convertObject($object, $type);
            } elseif (is_array($object)) {
                $objects[self::convertString($key, $type)] = self::convertArray($object, $type);
            } else {
                $objects[self::convertString($key, $type)] = $object;
            }
        }

        return $objects;
    }

    private static function convertObject($param, $type = 'CAMEL')
    {
        if ($param instanceof Model) {
            $param = $param->getAttributes();
        }
        $keys = array_keys((array) $param);
        $object = [];
        foreach ($keys as $key => $value) {
            if (is_array(((array) $param)[$value])) {
                $object[self::convertString($value, $type)] = self::convertArray(((array) $param)[$value], $type);
            } elseif (is_object(((array) $param)[$value])) {
                $object[self::convertString($value, $type)] = self::convertObject(((array) $param)[$value], $type);
            } else {
                $object[self::convertString($value, $type)] = ((array) $param)[$value];
            }
        }

        return $object;
    }
}
