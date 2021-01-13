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
    ];

    protected $supported_filter_operator = [
        'containts',
        '=',
    ];

    protected $protected_tables = [
        'data_rows',
        'data_types',
        'migrations',
        'password_resets',
        'menus',
        'menu_items',
        'users',
        'roles',
        'permissions',
        'configurations',
        'role_permissions',
        'user_roles',
    ];

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

    public function getProtectedTables()
    {
        return $this->protected_tables;
    }
}
