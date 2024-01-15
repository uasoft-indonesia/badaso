<?php

namespace Uasoft\Badaso;

use Illuminate\Support\Str;
use Uasoft\Badaso\Models\Configuration;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\RolePermission;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class Badaso
{
    protected $models = [
        'DataRow' => DataRow::class,
        'DataType' => DataType::class,
        'Permission' => Permission::class,
        'Role' => Role::class,
        'User' => User::class,
        'UserRole' => UserRole::class,
        'RolePermission' => RolePermission::class,
        'Menu' => Menu::class,
        'MenuItem' => MenuItem::class,
        'Configuration' => Configuration::class,
    ];

    protected $supported_component = [
        'text',
        'email',
        'password',
        'textarea',
        'checkbox',
        'search',
        'number',
        'url',
        'time',
        'date',
        'datetime',
        'select',
        'select_multiple',
        'radio',
        'switch',
        'slider',
        'editor',
        'tags',
        'color_picker',
        'upload_image',
        'upload_image_multiple',
        'upload_file',
        'upload_file_multiple',
        'hidden',
        'code',
        'relation',
        'data_identifier',
    ];

    protected $supported_filter_operator = [
        'containts',
        '=',
    ];

    protected $supported_table_relations = [
        'belongs_to',
        'has_one',
        'has_many',
        'belongs_to_many',
    ];

    protected $protected_tables = [
        'activity_log',
        'data_rows',
        'data_types',
        'migrations',
        'password_resets',
        'menus',
        'menu_items',
        'roles',
        'permissions',
        'configurations',
        'role_permissions',
        'user_roles',
        'user_verifications',
        'f_c_m_messages',
        'firebase_cloud_messages',
        'firebase_services',
    ];

    protected $badaso_cloud_api = 'https://badaso.uatech.co.id';

    protected $badaso_dbms_field_type = '
    [
        {
           "title": "Frequently used",
           "group": [
                {
                    "label": "Integer",
                    "value": "integer"
                },
                {
                    "label": "Varchar",
                    "value": "varchar"
                },
                {
                    "label": "Text",
                    "value": "text"
                },
                {
                    "label": "Date",
                    "value": "date"
                }
           ]
        },
        {
           "title": "Numeric",
           "group": [
                {
                    "label": "Tiny Integer",
                    "value": "tinyint"
                },
                {
                    "label": "Small Integer",
                    "value": "smallint"
                },
                {
                    "label": "Medium Integer",
                    "value": "mediumint"
                },
                {
                    "label": "Big Integer",
                    "value": "bigint"
                },
                {
                    "label": "Decimal",
                    "value": "decimal"
                },
                {
                    "label": "Float",
                    "value": "float"
                },
                {
                    "label": "Double",
                    "value": "double"
                },
                {
                    "label": "Boolean",
                    "value": "boolean"
                }
           ]
        },
        {
           "title": "Date and Time",
           "group": [
                {
                    "label": "Datetime",
                    "value": "datetime"
                },
                {
                    "label": "Time",
                    "value": "time"
                },
                {
                    "label": "Year",
                    "value": "year"
                },
                {
                    "label": "Timestamp",
                    "value": "timestamp"
                }
           ]
        },
        {
           "title": "String",
           "group": [
                {
                    "label": "Char",
                    "value": "char"
                },
                {
                    "label": "Medium Text",
                    "value": "mediumtext"
                },
                {
                    "label": "Long Text",
                    "value": "longtext"
                },
                {
                    "label": "BLOB",
                    "value": "blob"
                },
                {
                    "label": "Enum",
                    "value": "enum"
                },
                {
                    "label": "Set",
                    "value": "set"
                }
           ]
        },
        {
           "title": "Spatial",
           "group": [
                {
                    "label": "Geometry",
                    "value": "geometry"
                },
                {
                    "label": "Point",
                    "value": "point"
                },
                {
                    "label": "Multi Point",
                    "value": "multipoint"
                },
                {
                    "label": "Polygon",
                    "value": "polygon"
                },
                {
                    "label": "Multi Polygon",
                    "value": "multipolygon"
                },
                {
                    "label": "Linestring",
                    "value": "linestring"
                },
                {
                    "label": "Multi Linestring",
                    "value": "multilinestring"
                },
                {
                    "label": "Geometry Collection",
                    "value": "geometrycollection"
                }
           ]
        },
        {
           "title": "JSON",
           "group": [
                {
                    "label": "JSON",
                    "value": "json"
                }
           ]
        }
    ]';

    public function model($name)
    {
        return app($this->models[Str::studly($name)]);
    }

    public function modelClass($name)
    {
        return $this->models[$name];
    }

    public function getComponents()
    {
        return $this->supported_component;
    }

    public function getFilterOperator()
    {
        return $this->supported_filter_operator;
    }

    public function getSupportedTableRelations()
    {
        return $this->supported_table_relations;
    }

    public static function getProtectedTables()
    {
        return config('badaso-hidden-tables', []);
    }

    public function getBadasoCloudApi()
    {
        return $this->badaso_cloud_api;
    }

    public function getBadasoVerifyApi()
    {
        return $this->badaso_cloud_api.'/api/verify-license';
    }

    public function getDefaultJwtTokenLifetime()
    {
        return 60 * 24; // a day
    }

    public function getBadasoDbmsFieldType()
    {
        return $this->badaso_dbms_field_type;
    }

    public static function getBadasoTablePrefix()
    {
        return config('badaso.database.prefix');
    }

    public function getConfig($key, $default_value = null)
    {
        $value = config($key);
        if (is_null($value) || $value == '') {
            return $default_value;
        }

        return $value;
    }
}
