<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Traits\FileHandler;

abstract class Controller extends BaseController
{
    use FileHandler;

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

    public function isAuthorize($method, $data_type)
    {
        if ($user = auth()->user()) {
            $permissions = DB::SELECT('
                SELECT * 
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                JOIN roles r ON rp.role_id  = r.id
                JOIN user_roles ur ON r.id = ur.role_id
                JOIN users u ON ur.user_id = u.id
                WHERE u.id = :user_id
                AND p.key = :permission
            ', [
                'user_id' => $user->id,
                'permission' => $method.'_'.$data_type->name,
            ]);

            if (count($permissions) > 0) {
                return true;
            }
        }

        return true;
    }

    public function createDataFromRaw($data_raws, $data_type)
    {
        $data = [];
        foreach ($data_raws as $data_raw) {
            $data[$data_raw['field']] = $data_raw['value'];
        }

        return $data;
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

    public function getContentByType($data_type, $data_row, $value)
    {
        $type = $data_row->type;
        $return_value = null;
        switch ($type) {
            case 'text':
                $return_value = $value;
                break;
            case 'password':
                $return_value = Hash::make($value);
                break;
            case 'textarea':
                $return_value = $value;
                break;
            case 'checkbox':
                $return_value = implode(',', collect($value)->pluck('value')->all());
                break;
            case 'search':
                $return_value = $value;
                break;
            case 'number':
                $return_value = $value;
                break;
            case 'url':
                $return_value = $value;
                break;
            case 'time':
                $z_removed = explode('.', $value)[0];
                $time = explode('T', $z_removed)[1];
                $return_value = $time;
                break;
            case 'date':
                $z_removed = explode('.', $value)[0];
                $date = explode('T', $z_removed)[0];
                $return_value = $date;
                break;
            case 'datetime':
                $z_removed = explode('.', $value)[0];
                $date_time = str_replace('T', ' ', $z_removed);
                $return_value = $date_time;
                break;
            case 'select':
                $return_value = $value;
                break;
            case 'select_multiple':
                $return_value = implode(',', $value);
                break;
            case 'radio':
                $return_value = $value;
                break;
            case 'switch':
                $return_value = $value;
                break;
            case 'slider':
                $return_value = $value;
                break;
            case 'editor':
                $return_value = $value;
                break;
            case 'tags':
                $return_value = $value;
                break;
            case 'color_picker':
                $return_value = $value;
                break;
            case 'upload_image':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_image_multiple':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_file':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_file_multiple':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'hidden':
                $return_value = $value;
                break;

            default:
                // code...
                break;
        }

        return $return_value;
    }

    public function getDataList($slug, $request)
    {
        DB::enableQueryLog();
        $data_type = $this->getDataType($slug);
        $fields = collect($data_type->dataRows)->where('browse', 1)->pluck('field')->all();
        $data = [];
        $records = [];
        $limit = isset($request['limit']) ? $request['limit'] : null;
        $offset = isset($request['offset']) ? $request['offset'] : null;
        $order_field = isset($request['order_field']) ? $request['order_field'] : null;
        $order_direction = isset($request['order_direction']) ? $request['order_direction'] : 'DESC';
        $filter_key = isset($request['filter_key']) ? $request['filter_key'] : null;
        $filter_operator = isset($request['filter_operator']) ? $request['filter_operator'] : 'containts';
        $filter_value = isset($request['filter_value']) ? $request['filter_value'] : '';

        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            if ($data_type->server_side) {
                $query = $model::query()->select($fields);
                if ($filter_key) {
                    switch ($filter_operator) {
                        case 'containts':
                            $filter_operator = 'LIKE';
                            $filter_value = "%{$filter_value}%";
                            break;

                        default:
                            // code...
                            break;
                    }
                    $query->where($filter_key, $filter_operator, $filter_value);
                }
                if ($limit) {
                    $query->limit($limit);
                }
                if ($offset) {
                    $query->offset($offset);
                }
                if ($order_field) {
                    $query->orderBy($order_field, $order_direction);
                }
                $data = $query->get();
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
            } else {
                $data = $model::query()->select($fields)->get();
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
            }
        } else {
            if ($data_type->server_side) {
                $query = DB::table($data_type->name)->select($fields);
                if ($filter_key) {
                    switch ($filter_operator) {
                        case 'containts':
                            $filter_operator = 'LIKE';
                            $filter_value = "%{$filter_value}%";
                            break;

                        default:
                            // code...
                            break;
                    }
                    $query->where($filter_key, $filter_operator, $filter_value);
                }
                if ($limit) {
                    $query->limit($limit);
                }
                if ($offset) {
                    $query->offset($offset);
                }
                if ($order_field) {
                    $query->orderBy($order_field, $order_direction);
                }
                $data = $query->get();
                $records = $data;
            } else {
                $data = DB::table($data_type->name)->select($fields)->get();
                $records = $data;
            }
        }

        return collect($records)->toArray();
    }

    public function getDataDetail($slug, $id)
    {
        $data_type = $this->getDataType($slug);
        $fields = collect($data_type->dataRows)->where('read', 1)->pluck('field')->all();
        $data = null;
        $record = null;
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $row = $model::query()->select($fields)->where('id', $id)->first();
            if ($row) {
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
            }
        } else {
            $record = DB::table($data_type->name)->select($fields)->where('id', $id)->first();
        }

        return $record;
    }

    public function insertData($data, $data_type)
    {
        $data_rows = collect($data_type->dataRows)->where('add', 1)->all();
        $model = null;
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (is_null($data_row)) {
                    // $model->{$key} = $value;
                } else {
                    $model->{$key} = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            $model->save();
        } else {
            $new_data = [];
            $timestamp = date('Y-m-d H:i:s');
            $new_data['created_at'] = $timestamp;
            $new_data['updated_at'] = $timestamp;
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (is_null($data_row)) {
                    // $new_data[$key] = $value;
                } else {
                    $new_data[$key] = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            $id = DB::table($data_type->name)->insertGetId($new_data);
            $model = DB::table($data_type->name)->where('id', $id)->first();
        }

        return $model;
    }

    public function updateData($data, $data_type)
    {
        $data_rows = collect($data_type->dataRows)->where('edit', 1)->all();
        $model = null;
        $id = $data['id'];
        $data = collect($data)->forget('id')->all();
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $model = $model::find($id);
            foreach ($data as $key => $value) {
                foreach ($data as $key => $value) {
                    $data_row = collect($data_rows)->where('field', $key)->first();
                    if (is_null($data_row)) {
                        // $model->{$key} = $value;
                    } else {
                        $model->{$key} = $this->getContentByType($data_type, $data_row, $value);
                    }
                }
            }
            $model->save();
        } else {
            $new_data = [];
            $new_data['updated_at'] = date('Y-m-d H:i:s');
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (is_null($data_row)) {
                    // $new_data[$key] = $value;
                } else {
                    $new_data[$key] = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            DB::table($data_type->name)->where('id', $id)->update($new_data);
            $model = DB::table($data_type->name)->where('id', $id)->first();
        }

        return $model;
    }

    public function deleteData($data, $data_type)
    {
        $data_rows = $data_type->dataRows;
        $model = null;
        $id = $data['id'];
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $model = $model::find($id);
            foreach ($data_rows as $data_row) {
                if (in_array($data_row->type, ['upload_image',
                    'upload_image_multiple',
                    'upload_file',
                    'upload_file_multiple', ])
                ) {
                    $files = explode(',', $model->{$data_row->field});
                    foreach ($files as $file) {
                        $this->handleDeleteFile($file);
                    }
                }
            }
            $model->delete();
        } else {
            $model = DB::table($data_type->name)->where('id', $id)->first();
            foreach ($data_rows as $data_row) {
                if (in_array($data_row->type, ['upload_image',
                    'upload_image_multiple',
                    'upload_file',
                    'upload_file_multiple', ])
                ) {
                    $files = explode(',', $model->{$data_row->field});
                    foreach ($files as $file) {
                        $this->handleDeleteFile($file);
                    }
                }
            }
            DB::table($data_type->name)->where('id', $id)->delete();
        }
    }

    public function getDataRelations($data)
    {
        $records = [];
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

        return $records;
    }
}
