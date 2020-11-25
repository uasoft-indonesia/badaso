<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use LogicException;
use ReflectionClass;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;

class BadasoBreadController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $table_name = env('DB_DATABASE', '');
            $tables = DB::select('SHOW TABLES');
            $key = 'Tables_in_'.$table_name;

            $tables = collect($tables)->whereNotIn($key, config('badaso.exclude_tables_from_bread', []))->all();

            $breads = [];
            foreach ($tables as $table) {
                $bread = [];
                $bread['table_name'] = $table->{$key};
                $bread['bread_data'] = Badaso::model('DataType')::where('name', $table->{$key})->first();
                $breads[] = $bread;
            }

            return ApiResponse::success(collect($breads)->toArray());
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $slug = $request->input('slug', '');
            $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
            if (is_null($data_type)) {
                throw new SingleException("Data type for {$slug} not found");
            }
            $class = new ReflectionClass(Badaso::modelClass('DataType'));
            $class_methods = $class->getMethods();
            foreach ($class_methods as $class_method) {
                if ($class_method->class == Badaso::modelClass('DataType')) {
                    try {
                        $data_type->{$class_method->name};
                    } catch (LogicException $e) {
                        $data_type[$class_method->name] = $data_type->{$class_method->name}();
                    }
                }
            }

            return ApiResponse::success($data_type);
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
                            $fail("Table {$value} does not exists");
                        }
                    },
                ],
                'rows' => 'required',
                'display_name_singular' => 'required',
            ]);
            $table_name = $request->input('name');

            $data_type = DataType::find($request->input('id'));
            $data_type->name = $table_name;
            $data_type->slug = $request->input('slug') ?? Str::slug($table_name);
            $data_type->display_name_singular = $request->input('display_name_singular');
            $data_type->display_name_plural = $request->input('display_name_plural') ?? Str::plural($data_type->display_name_singular);
            $data_type->icon = $request->input('icon');
            $data_type->model_name = $request->input('model_name');
            $data_type->policy_name = $request->input('policy_name');
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
                                $fail("Invalid rows, Field $table_name.{$value} does not exists");
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
                $new_data_row->required = isset($data_row['required']) ?? false;
                $new_data_row->browse = isset($data_row['browse']) ?? false;
                $new_data_row->read = isset($data_row['read']) ?? false;
                $new_data_row->edit = isset($data_row['edit']) ?? false;
                $new_data_row->add = isset($data_row['add']) ?? false;
                $new_data_row->delete = isset($data_row['delete']) ?? false;
                $new_data_row->details = isset($data_row['details']) ?? '';
                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            Permission::generateFor($data_type->name);

            if ($data_type->generate_permissions) {
                $this->addEditMenuItem($data_type);
            } else {
                Permission::removeFrom($data_type->name);
            }

            $data_type->data_rows = $new_data_rows;

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
                            $fail("Table {$value} does not exists");
                        }
                    },
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
            $new_data_type->description = $request->input('description');
            $new_data_type->generate_permissions = $request->input('generate_permissions');
            $new_data_type->server_side = $request->input('server_side');
            $new_data_type->details = $request->input('details');
            $new_data_type->controller = $request->input('controller');
            $new_data_type->save();

            $data_rows = $request->input('rows') ?? [];
            $new_data_rows = [];
            foreach ($data_rows as $index => $data_row) {
                Validator::make($data_row, [
                    'field' => [
                        'required',
                        function ($attribute, $value, $fail) use ($table_name) {
                            if (!Schema::hasColumn($table_name, $value)) {
                                $fail("Invalid rows, Field $table_name.{$value} does not exists");
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
                $new_data_row->required = isset($data_row['required']) ?? false;
                $new_data_row->browse = isset($data_row['browse']) ?? false;
                $new_data_row->read = isset($data_row['read']) ?? false;
                $new_data_row->edit = isset($data_row['edit']) ?? false;
                $new_data_row->add = isset($data_row['add']) ?? false;
                $new_data_row->delete = isset($data_row['delete']) ?? false;
                $new_data_row->details = isset($data_row['details']) ?? '';
                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            $new_data_type->data_rows = $new_data_rows;

            if ($new_data_type->generate_permissions) {
                Permission::generateFor($new_data_type->name);
            }

            $this->addEditMenuItem($new_data_type);

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
                'id' => 'required',
            ]);

            $data_type = DataType::find($request->id);

            Permission::removeFrom($data_type->name);

            $data_type->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function generate(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'table' => 'required',
            ]);

            $columns = DB::getSchemaBuilder()->getColumnListing($request->table);

            $new_data_type = new DataType();
            $new_data_type->name = $request->table;
            $new_data_type->slug = Str::slug($request->table);
            $new_data_type->display_name_singular = Str::studly($request->table);
            $new_data_type->display_name_plural = Str::plural($new_data_type->display_name_singular);
            $new_data_type->save();

            foreach ($columns as $index => $column) {
                $new_data_row = new DataRow();
                $new_data_row->data_type_id = $new_data_type->id;
                $new_data_row->field = $column;
                $new_data_row->type = DB::getSchemaBuilder()->getColumnType($request->table, $column);
                $new_data_row->display_name = Str::studly($column);
                $new_data_row->required = false;
                $new_data_row->browse = true;
                $new_data_row->read = true;
                $new_data_row->edit = true;
                $new_data_row->add = true;
                $new_data_row->delete = true;
                $new_data_row->details = '';
                $new_data_row->order = $index + 1;
                $new_data_row->save();
            }

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

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title' => $data_type->display_name_plural,
            'url' => $data_type->name,
        ]);

        if (!$menuItem->exists) {
            $order = $menuItem->highestOrderMenuItem();
            $menuItem->fill([
                'target' => '_self',
                'icon_class' => null,
                'color' => null,
                'parent_id' => null,
                'order' => $order,
                'permissions' => $data_type->generate_permissions ? 'browse_'.$data_type->name : null,
            ])->save();
        }
    }
}
