<?php

namespace Uasoft\Badaso\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class GetData
{
    public static function serverSideWithModel($data_type, $builder_params, $only_data_soft_delete = false)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $ids = collect($data_type->dataRows)->where('field', 'id')->pluck('field')->all();
        $fields = array_merge($fields, $ids);

        $model = app($data_type->model_name);
        $limit = $builder_params['limit'];
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];
        $filter_key = $builder_params['filter_key'];
        $filter_operator = $builder_params['filter_operator'];
        $filter_value = $builder_params['filter_value'];

        $records = [];
        $query = $model::query()->select($fields);

        // soft delete implement
        $is_soft_delete = $data_type->is_soft_delete;
        if ($is_soft_delete) {
            if ($only_data_soft_delete) {
                $query->whereNotNull('deleted_at');
            } else {
                $query->whereNull('deleted_at');
            }
        }
        // end

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
            $record = $row;
            foreach ($class_methods as $class_method) {
                if ($class_method->class == $class->name) {
                    try {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}));
                    } catch (Exception $e) {
                        // $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                    }
                }
            }
            $records[] = self::getRelationData($data_type, $record);
        }
        $data->setCollection(collect($records));

        return $data;
    }

    public static function clientSideWithModel($data_type, $builder_params, $only_data_soft_delete = false)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $ids = collect($data_type->dataRows)->where('field', 'id')->pluck('field')->all();
        $fields = array_merge($fields, $ids);

        $model = app($data_type->model_name);
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];

        $records = [];

        if ($order_field) {
            $data = $model::query()->select($fields)->orderBy($order_field, $order_direction);
        } else {
            $data = $model::query()->select($fields);
        }
        // soft delete implement
        $is_soft_delete = $data_type->is_soft_delete;
        if ($is_soft_delete) {
            if ($only_data_soft_delete) {
                $data = $data->whereNotNull('deleted_at');
            } else {
                $data = $data->whereNull('deleted_at');
            }
        }
        // end
        $data = $data->get();

        foreach ($data as $row) {
            $class = new ReflectionClass(get_class($row));
            $class_methods = $class->getMethods();

            $record = $row;
            foreach ($class_methods as $class_method) {
                if ($class_method->class == $class->name) {
                    try {
                        $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}));
                    } catch (Exception $e) {
                        // $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                    }
                }
            }

            $records[] = self::getRelationData($data_type, $record);
        }

        $entities['data'] = $records;
        $entities['total'] = count($records);

        return $entities;
    }

    public static function serverSideWithQueryBuilder($data_type, $builder_params, $only_data_soft_delete = false)
    {
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $ids = collect($data_type->dataRows)->where('field', 'id')->pluck('field')->all();
        $fields = array_merge($fields, $ids);

        $limit = $builder_params['limit'];
        $page = $builder_params['page'];
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];
        $filter_key = $builder_params['filter_key'];
        $filter_operator = $builder_params['filter_operator'];
        $filter_value = $builder_params['filter_value'];

        $query = DB::table($data_type->name)->select($fields);

        // soft delete implement
        $is_soft_delete = $data_type->is_soft_delete;
        if ($is_soft_delete) {
            if ($only_data_soft_delete) {
                $query = $query->whereNotNull('deleted_at');
            } else {
                $query = $query->whereNull('deleted_at');
            }
        }
        // end

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
        $data = $paginate->get();

        $collection = $data;

        $records = [];
        foreach ($collection as $row) {
            $records[] = self::getRelationData($data_type, $row);
        }

        $data = collect($records);

        $entities['data'] = $data;
        $entities['from'] = $page ? (($page - 1) * $limit) + 1 : 0;
        $entities['to'] = 0;
        if ($page) {
            $entities['to'] = count($data) > $limit ? $page * $limit : (($page - 1) * $limit) + count($data);
        }
        $entities['total'] = $total;

        return $entities;
    }

    public static function clientSideWithQueryBuilder($data_type, $builder_params, $only_data_soft_delete = false)
    {
        $data_rows = collect($data_type->dataRows);
        $fields = $data_rows->where('browse', 1)->pluck('field')->all();
        $ids = $data_rows->where('field', 'id')->pluck('field')->all();
        $fields = array_merge($fields, $ids);
        $order_field = $builder_params['order_field'];
        $order_direction = $builder_params['order_direction'];

        if ($order_field) {
            $records = DB::table($data_type->name)->select($fields)->orderBy($order_field, $order_direction);
        } else {
            $records = DB::table($data_type->name)->select($fields);
        }

        // soft delete implement
        $is_soft_delete = $data_type->is_soft_delete;
        if ($is_soft_delete) {
            if ($only_data_soft_delete) {
                $records = $records->whereNotNull('deleted_at');
            } else {
                $records = $records->whereNull('deleted_at');
            }
        }
        // end
       
        $records = $records->get()->map(function ($record) use ($data_rows) {
            foreach ($data_rows as $index => $data_row) {
                if ($data_row->type == 'upload_image_multiple') {
                    if (isset($record->{$data_row->field})) {
                        $upload_image_multiples = json_decode($record->{$data_row->field}, true);
                        if (isset($upload_image_multiples)) {
                            $upload_image_multiples = collect($upload_image_multiples)->map(function ($upload_image_multiple) {
                                if(config('lfm.should_create_thumbnails') == true){
                                $put_thumbs = config('lfm.thumb_folder_name');
                                $upload_image_multiple = explode('/', $upload_image_multiple);
                                $file_name = $upload_image_multiple[count($upload_image_multiple) - 1];
                                $upload_image_multiple[count($upload_image_multiple) - 1] = $put_thumbs;
                                $upload_image_multiple[] = $file_name;
                                $upload_image_multiple = join('/', $upload_image_multiple);
                                }
                                $asset = asset('storage/'.$upload_image_multiple);

                                return $asset;
                            });
                            $upload_image_multiples = implode(',', json_decode($upload_image_multiples));
                        }
                        $record->{$data_row->field} = $upload_image_multiples;
                    }
                } elseif ($data_row->type == 'upload_image') {
                    if (isset($record->{$data_row->field})) {
                        $upload_image = $record->{$data_row->field};
                        if (isset($upload_image)) {
                            if(config('lfm.should_create_thumbnails') == true){
                            $put_thumbs = config('lfm.thumb_folder_name');
                            $upload_image = explode('/', $upload_image);
                            $file_name = $upload_image[count($upload_image) - 1];
                            $upload_image[count($upload_image) - 1] = $put_thumbs;
                            $upload_image[] = $file_name;
                            $upload_image = join('/', $upload_image);
                            }
                            $upload_image = asset('storage/'.$upload_image);
                            $record->{$data_row->field} = $upload_image;
                        }
                    }
                }
            }

            return $record;
        });

        $data = [];

        foreach ($records as $row) {
            $data[] = self::getRelationData($data_type, $row);
        }

        $entities['data'] = $data;
        $entities['total'] = count($data);

        return $entities;
    }

    public static function getRelationData($data_type, $row)
    {
        $relational_fields = collect($data_type->dataRows)->filter(function ($value, $key) {
            return $value->relation != null;
        })->all();

        foreach ($relational_fields as $field) {
            $relation_detail = [];

            try {
                $relation_detail = is_string($field->relation) ? json_decode($field->relation) : $field->relation;
                $relation_detail = CaseConvert::snake($relation_detail);
            } catch (\Exception $e) {
            }

            $relation_type = array_key_exists('relation_type', $relation_detail) ? $relation_detail['relation_type'] : null;
            $destination_table = array_key_exists('destination_table', $relation_detail) ? $relation_detail['destination_table'] : null;
            $destination_table_column = array_key_exists('destination_table_column', $relation_detail) ? $relation_detail['destination_table_column'] : null;
            $destination_table_display_column = array_key_exists('destination_table_display_column', $relation_detail) ? $relation_detail['destination_table_display_column'] : null;

            if (
                $relation_type
                && $destination_table
                && $destination_table_column
                && $destination_table_display_column
            ) {
                if (isset($row->{$field->field})) {
                    $relation_data = DB::table($destination_table)->select([
                        $destination_table_column,
                        $destination_table_display_column,
                    ])
                        ->where($destination_table_column, $row->{$field->field})
                        ->get();

                    switch ($relation_type) {
                        case 'belongs_to':
                            $row->{$destination_table} = collect($relation_data)->first();
                            break;

                        case 'has_many':
                            $row->{$destination_table} = collect($relation_data)->toArray();
                            break;

                        case 'has_many':
                            $row->{$destination_table} = collect($relation_data)->first();
                            break;

                        default:
                            // code...
                            break;
                    }
                }
            }
        }

        return $row;
    }
}
