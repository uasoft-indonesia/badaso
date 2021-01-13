<?php

namespace Uasoft\Badaso\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class GetData
{
    public static function serverSideWithModel($data_type, $builder_params)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $model = app($data_type->model_name);
        $limit = $builder_params['limit'];
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];
        $filter_key = $builder_params['filter_key'];
        $filter_operator = $builder_params['filter_operator'];
        $filter_value = $builder_params['filter_value'];

        $records = [];
        $query = $model::query()->select($fields);
        // if ($filter_key) {
        //     switch ($filter_operator) {
        //         case 'containts':
        //             $filter_operator = 'LIKE';
        //             $filter_value = "%{$filter_value}%";
        //             break;

        //         default:
        //             // code...
        //             break;
        //     }
        //     $query->where($filter_key, $filter_operator, $filter_value);
        // }
        if ($filter_value) {
            foreach ($fields as $index => $field) {
                if ($index == 0) {
                    $query->where($field, 'LIKE', "%{$filter_value}%");
                } else {
                    $query->orWhere($field, 'LIKE', "%{$filter_value}%");
                }
            }
        }
        if ($order_field) {
            $query->orderBy($order_field, $order_direction);
        }
        $data = $query->paginate($limit ? $limit : 10);
        $collection = $data->getCollection();
        foreach ($collection as $row) {
            $class = new ReflectionClass(get_class($row));
            $class_methods = $class->getMethods();
            $record = json_decode(json_encode($row));
            foreach ($class_methods as $class_method) {
                if ($class_method->class == $class->name) {
                    try {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}));
                    } catch (Exception $e) {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                    }
                }
            }
            $records[] = $record;
        }
        $data->setCollection(collect($records));

        return $data;
    }

    public static function clientSideWithModel($data_type, $builder_params)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $model = app($data_type->model_name);
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];

        $records = [];

        if ($order_field) {
            $data = $model::query()->select($fields)->orderBy($order_field, $order_direction)->get();
        } else {
            $data = $model::query()->select($fields)->get();
        }
        foreach ($data as $row) {
            $class = new ReflectionClass(get_class($row));
            $class_methods = $class->getMethods();

            $record = json_decode(json_encode($row));
            foreach ($class_methods as $class_method) {
                if ($class_method->class == $class->name) {
                    try {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}));
                    } catch (Exception $e) {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                    }
                }
            }
            $records[] = $record;
        }

        $entities['data'] = $records;
        $entities['total'] = count($records);

        return $entities;
    }

    public static function serverSideWithQueryBuilder($data_type, $builder_params)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $limit = $builder_params['limit'];
        $page = $builder_params['page'];
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];
        $filter_key = $builder_params['filter_key'];
        $filter_operator = $builder_params['filter_operator'];
        $filter_value = $builder_params['filter_value'];

        $query = DB::table($data_type->name)->select($fields);
        // if ($filter_key) {
        //     switch ($filter_operator) {
        //         case 'containts':
        //             $filter_operator = 'LIKE';
        //             $filter_value = "%{$filter_value}%";
        //             break;

        //         default:
        //             // code...
        //             break;
        //     }
        //     $query->where($filter_key, $filter_operator, $filter_value);
        // }
        if ($filter_value) {
            foreach ($fields as $index => $field) {
                if ($index == 0) {
                    $query->where($field, 'LIKE', "%{$filter_value}%");
                } else {
                    $query->orWhere($field, 'LIKE', "%{$filter_value}%");
                }
            }
        }
        $paginate = $query;
        $total = $query->count();

        if ($limit) {
            $paginate->limit($limit);
        }
        if ($page) {
            $paginate->offset(($page - 1) * $limit);
        }
        if ($order_field) {
            $paginate->orderBy($order_field, $order_direction);
        }
        $records = $paginate->get();

        $entities['data'] = $records;
        $entities['total'] = $total;

        return $entities;
    }

    public static function clientSideWithQueryBuilder($data_type, $builder_params)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];

        if ($order_field) {
            $records = DB::table($data_type->name)->select($fields)->orderBy($order_field, $order_direction)->get();
        } else {
            $records = DB::table($data_type->name)->select($fields)->get();
        }

        $entities['data'] = $records;
        $entities['total'] = count($records);

        return $entities;
    }
}
