<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\CaseConvert;
use Uasoft\Badaso\Helpers\GetData;
use Uasoft\Badaso\Traits\FileHandler;

abstract class Controller extends BaseController
{
    use FileHandler;

    public function getSlug(Request $request)
    {
        $slug = explode('.', $request->route()->getName())[1];

        return $slug;
    }

    public function getDataType($slug)
    {
        $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
        $data_type->data_rows = $data_type->dataRows;

        return $data_type;
    }

    public function isAuthorize($method, $data_type)
    {
        $prefix = config('badaso.database.prefix');
        if ($user = auth()->user()) {
            $permissions = DB::SELECT('
                SELECT *
                FROM ' . $prefix . 'permissions p
                JOIN ' . $prefix . 'role_permissions rp ON p.id = rp.permission_id
                JOIN ' . $prefix . 'roles r ON rp.role_id  = r.id
                JOIN ' . $prefix . 'user_roles ur ON r.id = ur.role_id
                JOIN ' . $prefix . 'users u ON ur.user_id = u.id
                WHERE u.id = :user_id
                AND p.key = :permission
            ', [
                'user_id'    => $user->id,
                'permission' => $method . '_' . $data_type->name,
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
                $rules[$row->field][] = 'required';
                if ($row->type == 'relation') {
                    $relation_detail = [];

                    try {
                        $relation_detail = is_string($row->relation) ? json_decode($row->relation) : $row->relation;
                        $relation_detail = CaseConvert::snake($relation_detail);
                    } catch (\Exception $e) {
                    }

                    $relation_type = array_key_exists('relation_type', $relation_detail) ? $relation_detail['relation_type'] : null;
                    $destination_table = array_key_exists('destination_table', $relation_detail) ? $relation_detail['destination_table'] : null;
                    $destination_table_column = array_key_exists('destination_table_column', $relation_detail) ? $relation_detail['destination_table_column'] : null;

                    if ($relation_type == 'belongs_to') {
                        $rules[$row->field][] = 'exists:' . $destination_table . ',' . $destination_table_column;
                    }
                }
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
                $return_value = implode(',', $value);
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
                $uploaded_path = $this->handleUploadFiles([$value], $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_image_multiple':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_file':
                $uploaded_path = $this->handleUploadFiles([$value], $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'upload_file_multiple':
                $uploaded_path = $this->handleUploadFiles($value, $data_type);
                $return_value = implode(',', $uploaded_path);
                break;
            case 'hidden':
                $return_value = $value;
                break;
            case 'relation':
                $return_value = $value;
                break;
            default:
                $return_value = $value;
                break;
        }

        return $return_value;
    }

    public function getDataList($slug, $request, $only_data_soft_delete = false)
    {
        $data_type = $this->getDataType($slug);
        $data = [];
        $records = [];
        $builder_params = [
            'limit'           => isset($request['limit']) ? $request['limit'] : 10,
            'page'            => isset($request['page']) ? $request['page'] : null,
            'order_field'     => isset($request['order_field']) ? $request['order_field'] : $data_type->order_column,
            'order_direction' => isset($request['order_direction']) ? $request['order_direction'] : $data_type->order_direction,
            'filter_key'      => isset($request['filter_key']) ? $request['filter_key'] : null,
            'filter_operator' => isset($request['filter_operator']) ? $request['filter_operator'] : 'containts',
            'filter_value'    => isset($request['filter_value']) ? $request['filter_value'] : '',
        ];

        if ($data_type->model_name) {
            if ($data_type->server_side) {
                $records = GetData::serverSideWithModel($data_type, $builder_params, $only_data_soft_delete);
            } else {
                $records = GetData::clientSideWithModel($data_type, $builder_params, $only_data_soft_delete);
            }
        } else {
            if ($data_type->server_side) {
                $records = GetData::serverSideWithQueryBuilder($data_type, $builder_params, $only_data_soft_delete);
            } else {
                $records = GetData::clientSideWithQueryBuilder($data_type, $builder_params, $only_data_soft_delete);
            }
        }

        return $records;
    }

    public function getDataDetail($slug, $id)
    {
        $data_type = $this->getDataType($slug);
        $fields = collect($data_type->dataRows)->where('read', 1)->pluck('field')->all();
        $ids = collect($data_type->dataRows)->where('field', 'id')->pluck('field')->all();
        $fields = array_merge($fields, $ids);
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
                            // $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                        }
                    }
                }
            }
        } else {
            $record = DB::table($data_type->name)->select($fields)->where('id', $id)->first();
        }

        $record = GetData::getRelationData($data_type, $record);

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
                if (!is_null($data_row)) {
                    $model->{$key} = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            $model->save();
        } else {
            $new_data = [];
            $timestamp = date('Y-m-d H:i:s');
            $data['created_at'] = $timestamp;
            $data['updated_at'] = $timestamp;
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (!is_null($data_row)) {
                    $new_data[$key] = $this->getContentByType($data_type, $data_row, $value);
                } else {
                    if (in_array($key, ['created_at', 'updated_at'])) {
                        $new_data[$key] = $value;
                    }
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
            $old_data = json_decode(json_encode($model));
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (is_null($data_row)) {
                    // $model->{$key} = $value;
                } else {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            if (is_array($value)) {
                                if (!in_array($file, $value)) {
                                    $this->handleDeleteFile($file);
                                }
                            } else {
                                if ($file != $value) {
                                    $this->handleDeleteFile($file);
                                }
                            }
                        }
                    }
                    $model->{$key} = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            $model->save();
        } else {
            $new_data = [];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $model = DB::table($data_type->name)->where('id', $id)->first();
            $old_data = json_decode(json_encode($model));
            foreach ($data as $key => $value) {
                $data_row = collect($data_rows)->where('field', $key)->first();
                if (is_null($data_row)) {
                    // $new_data[$key] = $value;
                } else {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            if (is_array($value)) {
                                if (!in_array($file, $value)) {
                                    $this->handleDeleteFile($file);
                                }
                            } else {
                                if ($file != $value) {
                                    $this->handleDeleteFile($file);
                                }
                            }
                        }
                    }
                    $new_data[$key] = $this->getContentByType($data_type, $data_row, $value);
                }
            }
            DB::table($data_type->name)->where('id', $id)->update($new_data);
            $model = DB::table($data_type->name)->where('id', $id)->first();
        }

        return [
            'old_data'     => $old_data,
            'updated_data' => $model,
        ];
    }

    public function deleteData($data, $data_type, $is_hard_delete = false)
    {
        $data_rows = $data_type->dataRows;
        $model = null;
        $id = $data['id'];
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $model = $model::find($id);
            if (!is_null($model)) {
                foreach ($data_rows as $data_row) {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            $this->handleDeleteFile($file);
                        }
                    }
                }
                if ($is_hard_delete) {
                    $model->delete();
                } else {
                    if ($data_type->is_soft_delete) {
                        $model->update([
                            'deleted_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $model->delete();
                    }
                }
            }
        } else {
            $model = DB::table($data_type->name)->where('id', $id)->first();
            if (!is_null($model)) {
                foreach ($data_rows as $data_row) {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            $this->handleDeleteFile($file);
                        }
                    }
                }
                $model = DB::table($data_type->name)->where('id', $id);

                if ($is_hard_delete) {
                    $model->delete();
                } else {
                    if ($data_type->is_soft_delete) {
                        $model->update([
                            'deleted_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $model->delete();
                    }
                }
            }
        }
    }

    public function restoreData($data, $data_type)
    {
        $data_rows = $data_type->dataRows;
        $model = null;
        $id = $data['id'];
        if ($data_type->model_name) {
            $model = app($data_type->model_name);
            $model = $model::find($id);
            if (!is_null($model)) {
                foreach ($data_rows as $data_row) {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            $this->handleDeleteFile($file);
                        }
                    }
                }
                $model->update([
                    'deleted_at' => null,
                ]);
            }
        } else {
            $model = DB::table($data_type->name)->where('id', $id)->first();
            if (!is_null($model)) {
                foreach ($data_rows as $data_row) {
                    if (in_array($data_row->type, [
                        'upload_image',
                        'upload_image_multiple',
                        'upload_file',
                        'upload_file_multiple',
                    ])) {
                        $files = explode(',', $model->{$data_row->field});
                        foreach ($files as $file) {
                            $this->handleDeleteFile($file);
                        }
                    }
                }
                $model = DB::table($data_type->name)->where('id', $id);

                $model->update([
                    'deleted_at' => null,
                ]);
            }
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
                        // $record->{$class_method->name} = json_decode(json_encode($row->{$class_method->name}()));
                    }
                }
            }
            $records[] = $record;
        }

        return $records;
    }

    protected function dataRowsTypeReplace(Collection $data_rows): Collection
    {
        if (env('DB_CONNECTION') == 'sqlite') {
            foreach ($data_rows as $index => $rows) {
                foreach ($rows->toArray() as $key => $value) {
                    if (is_numeric($value)) {
                        if (is_double($value)) {
                            $value = doubleval($value);
                        } else {
                            $value = intval($value);
                        }
                    }

                    $data_rows[$index][$key] = $value;
                }
            }
        }

        return $data_rows;
    }
}
