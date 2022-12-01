<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Filesystem\Filesystem as LaravelFileSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Database\Schema\SchemaManager;
use Uasoft\Badaso\Events\CRUDDataAdded;
use Uasoft\Badaso\Events\CRUDDataDeleted;
use Uasoft\Badaso\Events\CRUDDataUpdated;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiDocs;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\DataTypeToComponent;
use Uasoft\Badaso\Helpers\WatchTableConfig;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;

class BadasoCRUDController extends Controller
{
    /** @var FileGenerator */
    private $file_generator;

    private $file_name;

    public function __construct(FileGenerator $file_generator)
    {
        $this->file_generator = $file_generator;
    }

    public function browse(Request $request)
    {
        try {
            // init table watch table
            $config_watch_tables = WatchTableConfig::get();
            // end init table watch table

            $protected_tables = Badaso::getProtectedTables();
            $tables = SchemaManager::listTables();

            $tables_with_crud_data = [];
            foreach ($tables as $key => $value) {
                if (! in_array($key, $protected_tables)) {
                    // add table watch config
                    $config_watch_tables->addWatchTable($key);
                    // end add table watch config

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
                'table' => 'required|exists:Uasoft\Badaso\Models\DataType,name',
            ]);
            $table = $request->input('table', '');
            $data_type = Badaso::model('DataType')::where('name', $table)->first();
            $data_rows = $this->dataRowsTypeReplace($data_type->dataRows);

            $table_fields = SchemaManager::describeTable($table);
            $generated_fields = collect($data_rows)->pluck('field')->toArray();

            foreach ($table_fields as $key => $column) {
                $field = $key;
                $column = collect($column)->toArray();
                if (! in_array($field, $generated_fields)) {
                    $data_row['data_type_id'] = $data_type->id;
                    $data_row['field'] = $key;
                    $data_row['type'] = DataTypeToComponent::convert($column['type']);
                    $data_row['displayName'] = Str::studly($field);
                    $data_row['required'] = $column['notnull'] ? 1 : 0;
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
                'slug' => 'required|exists:Uasoft\Badaso\Models\DataType,slug',
            ]);
            $slug = $request->input('slug', '');
            $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
            $data_rows = $this->dataRowsTypeReplace($data_type->dataRows);

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
        $this->addTablePolymorphism($request);
        DB::beginTransaction();

        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Models\DataType',
                'name' => [
                    'required',
                    "unique:Uasoft\Badaso\Models\DataType,name,{$request->id}",
                    function ($attribute, $value, $fail) {
                        if (! Schema::hasTable($value)) {
                            $fail(__('badaso::validation.crud.table_not_found', ['table' => $value]));
                        }
                    },
                ],
                'rows' => 'required',
                'rows.*.field' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        if (! Schema::hasColumn($request->name, $value)) {
                            $split_attribute = explode('.', $attribute);
                            $split_attribute[2] = 'relation_type';
                            $field_to_relation = join('.', $split_attribute);
                            if (! $field_to_relation == 'belongs_to_many') {
                                $request->{$attribute} == $value ? $value : $fail(__('badaso::validation.crud.table_column_not_found', ['table_column' => "$request->name.{$value}"]));
                            }
                        } else {
                            $table_fields = SchemaManager::describeTable($request->name);
                            $field = collect($table_fields)->where('field', $value)->first();
                            $row = collect($request->rows)->where('field', $value)->first();
                            if (! $row['add'] && ! $field['autoincrement'] && $field['notnull'] && is_null($field['default'])) {
                                $fail(__('badaso::validation.crud.table_column_not_have_default_value', ['table_column' => "$request->name.{$value}"]));
                            } elseif ($row['field'] != 'id' && $field['key'] == 'PRI') {
                                $fail(__('badaso::validation.crud.id_table_wrong', ['table_column' => "$request->name.{$value}"]));
                            }
                        }
                    },
                ],
                'rows.*.type' => 'required',
                'rows.*.display_name' => 'required',
                'display_name_singular' => 'required',
                'notification.*.event' => ['in:onCreate,onRead,onUpdate,onDelete'],
                'create_soft_delete' => ['required', 'boolean', function ($att, $val, $failed) use ($request) {
                    if (isset($request->name) && $val) {
                        if (! Schema::hasColumn($request->name, 'deleted_at')) {
                            $failed(__('badaso::validation.crud.table_deleted_at_not_exists', [
                                'table_name' => $request->name,
                            ]));
                        }
                    }
                }],
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
            $data_type->order_column = $request->input('order_column');
            $data_type->order_display_column = $request->input('order_display_column');
            $data_type->order_direction = $request->input('order_direction');
            $data_type->description = $request->input('description');
            $data_type->generate_permissions = $request->input('generate_permissions');
            $data_type->server_side = $request->input('server_side');
            $data_type->details = $request->input('details');
            $data_type->controller = $request->input('controller');
            $data_type->notification = json_encode($request->input('notification'));
            $data_type->is_soft_delete = $request->input('create_soft_delete');
            $data_type->save();

            DataRow::where('data_type_id', $data_type->id)->delete();

            $data_rows = $request->input('rows') ?? [];
            $new_data_rows = [];
            foreach ($data_rows as $index => $data_row) {
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
                    if (isset($data_row['destination_table_display_more_column'])) {
                        $relation['destination_table_display_more_column'] = $data_row['destination_table_display_more_column'];
                    }
                    if (in_array(count($relation), range(4, 5))) {
                        $new_data_row->relation = json_encode($relation);
                    }
                }
                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            if ($data_type->generate_permissions) {
                Permission::generateFor($data_type->name, true);
            } else {
                Permission::removeFrom($data_type->name);
            }

            $this->addEditMenuItem($data_type);

            $data_type->data_rows = $new_data_rows;

            event(new CRUDDataUpdated($data_type, null));

            $this->generateAPIDocs($table_name, $data_rows, $data_type);
            DB::commit();

            return ApiResponse::success($data_type);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        $this->addTablePolymorphism($request);

        DB::beginTransaction();

        try {
            $request->validate([
                'name' => [
                    'required',
                    'unique:Uasoft\Badaso\Models\DataType',
                    function ($attribute, $value, $fail) {
                        if (! Schema::hasTable($value)) {
                            $fail(__('badaso::validation.crud.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
                'rows' => 'required',
                'rows.*.field' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        if (! Schema::hasColumn($request->name, $value)) {
                            $split_attribute = explode('.', $attribute);
                            $split_attribute[2] = 'relation_type';
                            $field_to_relation = join('.', $split_attribute);
                            if (! $field_to_relation == 'belongs_to_many') {
                                $fail(__('badaso::validation.crud.table_column_not_found', ['table_column' => "$request->name.{$value}"]));
                            }
                        } else {
                            $table_fields = SchemaManager::describeTable($request->name);
                            $field = collect($table_fields)->where('field', $value)->first();
                            $row = collect($request->rows)->where('field', $value)->first();
                            if (! $row['add'] && ! $field['autoincrement'] && $field['notnull'] && is_null($field['default'])) {
                                $fail(__('badaso::validation.crud.table_column_not_have_default_value', ['table_column' => "$request->name.{$value}"]));
                            } elseif ($row['field'] != 'id' && $field['key'] == 'PRI') {
                                $fail(__('badaso::validation.crud.id_table_wrong', ['table_column' => "$request->name.{$value}"]));
                            }
                        }
                    },
                ],
                'rows.*.type' => 'required',
                'rows.*.display_name' => 'required',
                'display_name_singular' => 'required',
                'notification.*.event' => ['in:onCreate,onRead,onUpdate,onDelete'],
                'create_soft_delete' => ['required', 'boolean', function ($att, $val, $failed) use ($request) {
                    if (isset($request->name) && $val) {
                        if (! Schema::hasColumn($request->name, 'deleted_at')) {
                            $failed(__('badaso::validation.crud.table_deleted_at_not_exists', [
                                'table_name' => $request->name,
                            ]));
                        }
                    }
                }],
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
            $new_data_type->notification = json_encode($request->input('notification'));
            $new_data_type->is_soft_delete = $request->input('create_soft_delete');
            $new_data_type->save();

            $data_rows = $request->input('rows') ?? [];
            $new_data_rows = [];
            foreach ($data_rows as $index => $data_row) {
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
                if (isset($data_row['destination_table_display_more_column'])) {
                    $relation['destination_table_display_more_column'] = $data_row['destination_table_display_more_column'];
                }
                if (in_array(count($relation), range(4, 5)) && $data_row['type'] == 'relation') {
                    $new_data_row->relation = json_encode($relation);
                }
                $new_data_row->order = $index + 1;
                $new_data_row->save();

                $new_data_rows[] = $new_data_row;
            }

            $new_data_type->data_rows = $new_data_rows;

            if ($new_data_type->generate_permissions) {
                Permission::generateFor($new_data_type->name, true);
            }

            $this->addEditMenuItem($new_data_type);

            event(new CRUDDataAdded($new_data_type, null));

            $this->generateAPIDocs($table_name, $data_rows, $new_data_type);

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity('CRUD')
                ->causedBy($user_auth ?? null)
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
                'id' => 'required|exists:Uasoft\Badaso\Models\DataType,id',
            ]);

            $data_type = DataType::find($request->id);

            $this->deleteAPIDocs($data_type->name);

            Permission::removeFrom($data_type->name);

            $this->deleteMenuItem($data_type);

            $data_type->delete();

            event(new CRUDDataDeleted($data_type));

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity('CRUD')
                ->causedBy($user_auth ?? null)
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
        $url = '/'.$menu_key.'/'.$data_type->slug;

        if (is_null($menu)) {
            $menu = new Menu();
            $menu->key = $menu_key;
            $menu->display_name = Str::studly($menu_key);
            $menu->save();
        }

        $menu_item = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'url' => $url,
        ]);

        $menu_item = MenuItem::where('menu_id', $menu->id)->where('url', $url)->first();
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
            $menu_item->url = $url;
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
        $menu_key = config('badaso.default_menu');
        $url = '/'.$menu_key.'/'.$data_type->slug;
        MenuItem::where('url', $url)->delete();
    }

    private function generateAPIDocs($table_name, $data_rows, $data_type)
    {
        $filesystem = new LaravelFileSystem();
        $file_path = ApiDocs::getFilePath($table_name);
        $stub = ApiDocs::getStub($table_name, $data_rows, $data_type);
        if (! $filesystem->put($file_path, $stub)) {
            return false;
        }

        return true;
    }

    private function deleteAPIDocs($table_name)
    {
        $filesystem = new LaravelFileSystem();
        $file_path = ApiDocs::getFilePath($table_name);
        if ($filesystem->exists($file_path)) {
            return $filesystem->delete($file_path);
        }

        return false;
    }

    private function addTablePolymorphism($request)
    {
        foreach ($request['rows'] as $key => $value) {
            if (isset($value['relation_type']) && $value['relation_type'] == 'belongs_to_many') {
                $table = $value['field'];
                $rows = [
                    0 => [
                        'id' => 'id',
                        'field_name' => 'id',
                        'field_type' => 'bigint',
                        'field_length' => null,
                        'field_null' => false,
                        'field_attribute' => true,
                        'field_increment' => true,
                        'field_index' => 'primary',
                        'field_default' => null,
                        'undeletable' => true,
                    ],
                    1 => [
                        'id' => $request['name'].'_id',
                        'field_name' => $request['name'].'_id',
                        'field_type' => 'bigint',
                        'field_length' => null,
                        'field_null' => false,
                        'field_attribute' => true,
                        'field_increment' => false,
                        'field_index' => 'foreign',
                        'field_default' => null,
                    ],
                    2 => [
                        'id' => $value['destination_table'].'_id',
                        'field_name' => $value['destination_table'].'_id',
                        'field_type' => 'bigint',
                        'field_length' => null,
                        'field_null' => false,
                        'field_attribute' => true,
                        'field_increment' => false,
                        'field_index' => 'foreign',
                        'field_default' => null,
                    ],
                    3 => [
                        'field_name' => 'created_at',
                        'field_type' => 'timestamp',
                        'field_length' => null,
                        'field_null' => true,
                        'field_attribute' => false,
                        'field_increment' => false,
                        'field_index' => null,
                        'field_default' => null,
                        'undeletable' => true,
                        'indexes' => true,
                    ],
                    4 => [
                        'field_name' => 'updated_at',
                        'field_type' => 'timestamp',
                        'field_length' => null,
                        'field_null' => true,
                        'field_attribute' => false,
                        'field_increment' => false,
                        'field_index' => null,
                        'field_default' => null,
                        'undeletable' => true,
                    ],
                ];

                $relations = [
                    $request['name'].'_id' => [
                        'source_field' => $request['name'].'_id',
                        'target_table' => $request['name'],
                        'target_field' => 'id',
                        'on_delete' => 'cascade',
                        'on_update' => 'restrict',
                    ],
                    $value['destination_table'].'_id' => [
                        'source_field' => $value['destination_table'].'_id',
                        'target_table' => $value['destination_table'],
                        'target_field' => 'id',
                        'on_delete' => 'cascade',
                        'on_update' => 'restrict',
                    ],
                ];
                if (! Schema::hasTable($table)) {
                    $this->file_name = $this->file_generator->generateBDOMigrationFile($table, 'create', $rows, $relations);
                    $exitCode = Artisan::call('migrate', [
                        '--path' => 'database/migrations/badaso/',
                        '--force' => true,
                    ]);
                }
            }
        }
    }
}
