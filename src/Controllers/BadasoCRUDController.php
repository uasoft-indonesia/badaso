<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use LogicException;
use ReflectionClass;
use Uasoft\Badaso\Database\Schema\SchemaManager;
use Uasoft\Badaso\Events\CRUDDataAdded;
use Uasoft\Badaso\Events\CRUDDataDeleted;
use Uasoft\Badaso\Events\CRUDDataUpdated;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\DataTypeToComponent;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;

class BadasoCRUDController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $protected_tables = Badaso::getProtectedTables();
            $tables = SchemaManager::listTables();
            $tables_with_crud_data = [];
            foreach ($tables as $key => $value) {
                if (!in_array($key, $protected_tables)) {
                    $table_with_crud_data = [];
                    $table_with_crud_data['table_name'] = $key;
                    $table_with_crud_data['crud_data'] = Badaso::model('DataType')::where('name', $key)->first();
                    $tables_with_crud_data[] = $table_with_crud_data;
                }
            }

            $data['tables_with_crud_data'] = $tables_with_crud_data;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'table' => 'required|exists:data_types,name',
            ]);
            $table = $request->input('table', '');
            $data_type = Badaso::model('DataType')::where('name', $table)->first();
            $data_rows = $data_type->dataRows;

            $table_fields = SchemaManager::describeTable($table);
            $generated_fields = collect($data_rows)->pluck('field')->toArray();

            foreach ($table_fields as $key => $column) {
                $field = $key;
                if (!in_array($field, $generated_fields)) {
                    $data_row['data_type_id'] = $data_type->id;
                    $data_row['field'] = $key;
                    $data_row['type'] = DataTypeToComponent::convert($column->type);
                    $data_row['displayName'] = Str::studly($field);
                    $data_row['required'] = $column->notnull ? 1 : 0;
                    $data_row['browse'] = 1;
                    $data_row['read'] = 1;
                    $data_row['edit'] = 0;
                    $data_row['add'] = 0;
                    $data_row['delete'] = 0;
                    $data_row['details'] = '{}';
                    $data_row['order'] = null;

                    $data_rows[] = $data_row;
                }
            }

            $data_type->data_rows = $data_rows;

            $data['crud'] = collect($data_type)->toArray();

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function readBySlug(Request $request)
    {
        try {
            $request->validate([
                'slug' => 'required|exists:data_types,slug',
            ]);
            $slug = $request->input('slug', '');
            $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
            $data_rows = $data_type->dataRows;

            $crud_data = $data_type;
            $crud_data->data_rows = collect($data_rows)->toArray();

            $data['crud_data'] = $crud_data;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:data_types',
                'name' => [
                    'required',
                    "unique:data_types,name,{$request->id}",
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.crud.table_not_found', ['table' => $value]));
                        }
                    },
                ],
                'rows' => 'required',
                'display_name_singular' => 'required',
            ]);
            $table_name = $request->input('name');

            $data_type = DataType::find($request->input('id'));

            activity('CRUD')
                ->causedBy(auth()->user() ?? null)
                ->withProperties([
                    'old' => $data_type,
                    'new' => $request->input()
                ])
                ->log('Table '.$data_type->slug.' has been edited');

            $data_type->name = $table_name;
            $data_type->slug = $request->input('slug') ?? Str::slug($table_name);
            $data_type->display_name_singular = $request->input('display_name_singular');
            $data_type->display_name_plural = $request->input('display_name_plural') ?? Str::plural($data_type->display_name_singular);
            $data_type->icon = $request->input('icon');
            $data_type->model_name = $request->input('model_name');
            $data_type->policy_name = $request->input('policy_name');
            $data_type->order_column = $request->input('order_column');
            $data_type->order_display_column = $request->input('order_display_column');
            $data_type->order_direction = $request->input('order_direction');
            $data_type->description = $request->input('description');
            $data_type->generate_permissions = $request->input('generate_permissions');
            $data_type->server_side = $request->input('server_side');
            $data_type->details = $request->input('details');
            $data_type->controller = $request->input('controller');
            $data_type->save();

            DataRow::where('data_type_id', $data_type->id)->delete();

            $data_rows = $request->input('rows') ?? [];
            $new_data_rows = [];
            foreach ($data_rows as $index => $data_row) {
                Validator::make($data_row, [
                    'field' => [
                        'required',
                        function ($attribute, $value, $fail) use ($table_name) {
                            if (!Schema::hasColumn($table_name, $value)) {
                                $fail(__('badaso::validation.crud.table_column_not_found', ['table_column' => "$table_name.{$value}"]));
                            }
                        },
                    ],
                    'type' => 'required',
                    'display_name' => 'required',
                ])->validate();

                $new_data_row = new DataRow();
                $new_data_row->data_type_id = $data_type->id;
                $new_data_row->field = $data_row['field'];
                $new_data_row->type = $data_row['type'];
                $new_data_row->display_name = $data_row['display_name'];
                $new_data_row->required = isset($data_row['required']) ? $data_row['required'] : false;
                $new_data_row->browse = isset($data_row['browse']) ? $data_row['browse'] : false;
                $new_data_row->read = isset($data_row['read']) ? $data_row['read'] : false;
                $new_data_row->edit = isset($data_row['edit']) ? $data_row['edit'] : false;
                $new_data_row->add = isset($data_row['add']) ? $data_row['add'] : false;
                $new_data_row->delete = isset($data_row['delete']) ? $data_row['delete'] : false;
                $new_data_row->details = isset($data_row['details']) ? $data_row['details'] : '';
                if ($data_row['type'] != 'relation') {
                    $new_data_row->relation = null;
                } else {
                    $relation = [];
                    if (isset($data_row['relation_type'])) {
                        $relation['relation_type'] = $data_row['relation_type'];
                    }
                    if (isset($data_row['destination_table'])) {
                        $relation['destination_table'] = $data_row['destination_table'];
                    }
                    if (isset($data_row['destination_table_column'])) {
                        $relation['destination_table_column'] = $data_row['destination_table_column'];
                    }
                    if (isset($data_row['destination_table_display_column'])) {
                        $relation['destination_table_display_column'] = $data_row['destination_table_display_column'];
                    }
                    if (count($relation) == 4) {
                        $new_data_row->relation = json_encode($relation);
                    }
                }

                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            if ($data_type->generate_permissions) {
                Permission::generateFor($data_type->name);
            } else {
                Permission::removeFrom($data_type->name);
            }

            $this->addEditMenuItem($data_type);

            $data_type->data_rows = $new_data_rows;

            event(new CRUDDataUpdated($data_type, null));

            DB::commit();

            return ApiResponse::success($data_type);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => [
                    'required',
                    'unique:data_types',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.crud.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
                'rows' => 'required',
                'display_name_singular' => 'required',
            ]);
            $table_name = $request->input('name');
            $new_data_type = new DataType();
            $new_data_type->name = $table_name;
            $new_data_type->slug = $request->input('slug') ?? Str::slug($table_name);
            $new_data_type->display_name_singular = $request->input('display_name_singular');
            $new_data_type->display_name_plural = $request->input('display_name_plural') ?? Str::plural($new_data_type->display_name_singular);
            $new_data_type->icon = $request->input('icon');
            $new_data_type->model_name = $request->input('model_name');
            $new_data_type->policy_name = $request->input('policy_name');
            $new_data_type->controller = $request->input('controller');
            $new_data_type->order_column = $request->input('order_column');
            $new_data_type->order_display_column = $request->input('order_display_column');
            $new_data_type->order_direction = $request->input('order_direction');
            $new_data_type->generate_permissions = $request->input('generate_permissions');
            $new_data_type->server_side = $request->input('server_side');
            $new_data_type->description = $request->input('description');
            $new_data_type->details = $request->input('details');
            $new_data_type->save();

            $data_rows = $request->input('rows') ?? [];
            $new_data_rows = [];
            foreach ($data_rows as $index => $data_row) {
                Validator::make($data_row, [
                    'field' => [
                        'required',
                        function ($attribute, $value, $fail) use ($table_name) {
                            if (!Schema::hasColumn($table_name, $value)) {
                                $fail(__('badaso::validation.crud.table_column_not_found', ['table_column' => "$table_name.{$value}"]));
                            }
                        },
                    ],
                    'type' => 'required',
                    'display_name' => 'required',
                ])->validate();

                $new_data_row = new DataRow();
                $new_data_row->data_type_id = $new_data_type->id;
                $new_data_row->field = $data_row['field'];
                $new_data_row->type = $data_row['type'];
                $new_data_row->display_name = $data_row['display_name'];
                $new_data_row->required = isset($data_row['required']) ? $data_row['required'] : false;
                $new_data_row->browse = isset($data_row['browse']) ? $data_row['browse'] : false;
                $new_data_row->read = isset($data_row['read']) ? $data_row['read'] : false;
                $new_data_row->edit = isset($data_row['edit']) ? $data_row['edit'] : false;
                $new_data_row->add = isset($data_row['add']) ? $data_row['add'] : false;
                $new_data_row->delete = isset($data_row['delete']) ? $data_row['delete'] : false;
                $new_data_row->details = isset($data_row['details']) ? $data_row['details'] : '';
                $relation = [];
                if (isset($data_row['relation_type'])) {
                    $relation['relation_type'] = $data_row['relation_type'];
                }
                if (isset($data_row['destination_table'])) {
                    $relation['destination_table'] = $data_row['destination_table'];
                }
                if (isset($data_row['destination_table_column'])) {
                    $relation['destination_table_column'] = $data_row['destination_table_column'];
                }
                if (isset($data_row['destination_table_display_column'])) {
                    $relation['destination_table_display_column'] = $data_row['destination_table_display_column'];
                }
                if (count($relation) == 4 && $data_row['type'] == 'relation') {
                    $new_data_row->relation = json_encode($relation);
                }
                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            $new_data_type->data_rows = $new_data_rows;

            if ($new_data_type->generate_permissions) {
                Permission::generateFor($new_data_type->name);
            }

            $this->addEditMenuItem($new_data_type);

            event(new CRUDDataAdded($new_data_type, null));

            activity('CRUD')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $new_data_type])
                ->log('Table '.$new_data_type->slug.' has been created');

            DB::commit();

            return ApiResponse::success($new_data_type);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:data_types,id',
            ]);

            $data_type = DataType::find($request->id);

            Permission::removeFrom($data_type->name);

            $this->deleteMenuItem($data_type);

            $data_type->delete();

            event(new CRUDDataDeleted($data_type));

            activity('CRUD')
                ->causedBy(auth()->user() ?? null)
                ->withProperties($data_type)
                ->log('Table '.$data_type->slug.' has been deleted');

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    private function addEditMenuItem($data_type)
    {
        $menu_key = config('badaso.default_menu');
        $menu = Menu::where('key', $menu_key)->first();
        if (is_null($menu)) {
            $menu = new Menu();
            $menu->key = $menu_key;
            $menu->display_name = Str::studly($menu_key);
            $menu->save();
        }

        $menu_item = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'url' => $data_type->slug,
        ]);
        $menu_item = MenuItem::where('menu_id', $menu->id)->where('url', $data_type->slug)->first();
        if ($menu_item) {
            $menu_item->title = $data_type->display_name_plural;
            $menu_item->target = '_self';
            $menu_item->icon_class = $data_type->icon;
            $menu_item->color = null;
            $menu_item->parent_id = null;
            $menu_item->permissions = $data_type->generate_permissions ? 'browse_'.$data_type->name : null;
            $menu_item->save();
        } else {
            $menu_item = new MenuItem();
            $menu_item->menu_id = $menu->id;
            $menu_item->url = $data_type->slug;
            $menu_item->title = $data_type->display_name_plural;
            $menu_item->target = '_self';
            $menu_item->icon_class = $data_type->icon;
            $menu_item->color = null;
            $menu_item->parent_id = null;
            $menu_item->permissions = $data_type->generate_permissions ? 'browse_'.$data_type->name : null;
            $menu_item->order = $menu_item->highestOrderMenuItem();
            $menu_item->save();
        }
    }

    private function deleteMenuItem($data_type)
    {
        MenuItem::where('url', $data_type->slug)->delete();
    }
}
