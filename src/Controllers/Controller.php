<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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

    public function getData($slug)
    {
        $data_type = $this->getDataType($slug);
        $data = [];
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $data = $model::query()->orderBy('created_at', 'desc')->get();
            foreach ($data as $row) {
                $class = new ReflectionClass(get_class($row));
                $class_methods = $class->getMethods();

                foreach ($class_methods as $class_method) {
                    if ($class_method->class == $class->name) {
                        try {
                            $row[$class_method->name] = $row->{$class_method->name};
                        } catch (Exception $e) {
                            $row[$class_method->name] = $row->{$class_method->name}();
                        }
                    }
                }
            }
        } else {
            $data[] = 'Model Not Found';
        }

        return $data;
    }
}
