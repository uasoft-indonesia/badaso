<?php

namespace Uasoft\Badaso;

use Illuminate\Support\Str;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;

class Badaso
{
    protected $models = [
        'DataRow' => DataRow::class,
        'DataType' => DataType::class,
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
}
