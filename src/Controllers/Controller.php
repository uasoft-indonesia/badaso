<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use Uasoft\Badaso\Facades\Badaso;

abstract class Controller extends BaseController
{
    public function getSlug(Request $request)
    {
        if (isset($this->slug)) {
            $slug = $this->slug;
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }

    public function getDataType($slug)
    {
        $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
        $class = new ReflectionClass(Badaso::modelClass('DataType'));
        $class_methods = $class->getMethods();
        foreach ($class_methods as $class_method) {
            if ($class_method->class == Badaso::modelClass('DataType')) {
                $data_type->{$class_method->name};
            }
        }

        return $data_type;
    }

    public function getDataList($slug)
    {
        $data_type = $this->getDataType($slug);
        $data = [];
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $data = $model::query()->get();
            foreach ($data as $row) {
                $class = new ReflectionClass(get_class($row));
                $class_methods = $class->getMethods();

                foreach ($class_methods as $class_method) {
                    if ($class_method->class == $class->name) {
                        try {
                            $row->{$class_method->name};
                        } catch (Exception $e) {
                            $row[$class_method->name] = $row->{$class_method->name}();
                        }
                    }
                }
            }
        } else {
            $data = DB::table($data_type->name)->get();
        }

        return $data;
    }

    public function getDataDetail($slug, $id)
    {
        $data_type = $this->getDataType($slug);
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $data = $model::query()->where('id', $id)->first();
            foreach ($data as $row) {
                $class = new ReflectionClass(get_class($row));
                $class_methods = $class->getMethods();

                foreach ($class_methods as $class_method) {
                    if ($class_method->class == $class->name) {
                        try {
                            $row->{$class_method->name};
                        } catch (Exception $e) {
                            $row[$class_method->name] = $row->{$class_method->name}();
                        }
                    }
                }
            }
        } else {
            $data = DB::table($data_type->name)->where('id', $id)->first();
        }
    }

    public function validateData($data, $data_type)
    {
        $data_rows = collect($data_type->dataRows)->where('add', 1)->all();
        $rules = [];
        foreach ($data_rows as $row) {
            if ($row->required == 1) {
                $rules[$row->field] = 'required';
            }
        }

        return Validator::make($data, $rules)->validate();
    }

    public function createDataFromRaw($data_raws)
    {
        $data = [];
        foreach ($data_raws as $data_raw) {
            $data[$data_raw['field']] = $data_raw['value'];
        }

        return $data;
    }
}
