<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Database\Schema\SchemaManager;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Migration;

class BadasoDatabaseController extends Controller
{
    /** @var FileGenerator */
    private $file_generator;

    private $file_name;

    public function __construct(FileGenerator $file_generator)
    {
        $this->file_generator = $file_generator;
    }

    // public function browse(Request $request)
    // {
    //     try {
    //         $protected_tables = Badaso::getProtectedTables();
    //         $tables = SchemaManager::listTables();
    //         $tables_with_crud_data = [];
    //         foreach ($tables as $key => $value) {
    //             if (!in_array($key, $protected_tables)) {
    //                 $table_with_crud_data = [];
    //                 $table_with_crud_data['table_name'] = $key;
    //                 $table_with_crud_data['crud_data'] = Badaso::model('DataType')::where('name', $key)->first();
    //                 $tables_with_crud_data[] = $table_with_crud_data;
    //             }
    //         }

    //         $data['tables_with_crud_data'] = $tables_with_crud_data;

    //         return ApiResponse::success(collect($data)->toArray());
    //     } catch (Exception $e) {
    //         return APIResponse::failed($e);
    //     }
    // }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'table' => [
                    'required',
                    'unique:data_types,slug',
                    function ($attribute, $value, $fail) {
                        if (Schema::hasTable($value)) {
                            $fail(__('badaso::validation.database.table_already_exists', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
                'rows' => 'required',
                'timestamp' => 'required|boolean',
            ]);

            $this->file_name = $this->file_generator->generateBDOMigrationFile($request->table, 'create', $request->rows, $request->timestamp);

            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/',
            ]);

            switch ($exitCode) {
                case 0:
                    $msg = __('badaso::validation.database.migration_success');

                    return ApiResponse::success($msg);
                    break;
                default:
                    if (isset($this->file_name)) {
                        $this->file_generator->deleteMigrationFiles($this->file_name);
                    }

                    return ApiResponse::failed(__('badaso::validation.database.migration_failed'));
            }
        } catch (Exception $e) {
            if (isset($this->file_name)) {
                $this->file_generator->deleteMigrationFiles($this->file_name);
            }

            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'table' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.database.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
            ]);

            $columns = SchemaManager::describeTable($request->table)->toArray();
            $filteredColumn = [];
            $timestamp = false;

            foreach ($columns as $key => $value) {
                if (in_array($key, ['updated_at', 'created_at']) && in_array($value['type'], ['timestamp'])) {
                    $timestamp = true;
                } else {
                    $filteredColumn[$key] = $value;
                }
            }

            return ApiResponse::success(['columns' => $filteredColumn, 'timestamp' => $timestamp]);
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        try {
            $request->validate([
                'table.current_name' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.database.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
                'table.modified_name' => [
                    'required',
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
                'fields.current_fields' => 'required|array',
                'fields.modified_fields' => 'nullable|array',
            ]);

            $data = $request->all();
            $fields = $data['fields'];
            $table = $data['table'];

            if (count($fields['modified_fields']) > 0) {
                $this->file_name[] = $this->file_generator->generateBDOAlterMigrationFile($table, collect($fields['modified_fields'])->sortBy('modify_type')->reverse()->toArray(), 'alter');
            }

            if ($table['current_name'] !== $table['modified_name']) {
                $this->file_name[] = $this->file_generator->generateBDOAlterMigrationFile($table, null, 'rename');
            }

            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/',
            ]);

            switch ($exitCode) {
                case 0:
                    return ApiResponse::success(__('badaso::validation.database.alter_migration_created', ['table' => $table['modified_name']]));
                    break;
                default:
                    foreach ($this->file_name as $name) {
                        $this->file_generator->deleteMigrationFiles($name);
                    }

                    return ApiResponse::failed(__('badaso::validation.database.migration_failed'));
            }
        } catch (Exception $e) {
            if (isset($this->file_name)) {
                foreach ($this->file_name as $name) {
                    $this->file_generator->deleteMigrationFiles($name);
                }
            }

            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'table' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.database.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
            ]);

            $columns = SchemaManager::describeTable($request->table)->all();

            $rows = array_map(function ($column) {
                return [
                    'field_name' => $column['name'],
                    'field_type' => $column['type'],
                    'field_null' => !$column['null'],
                    'field_increment' => $column['autoincrement'],
                    'field_length' => $column['length'],
                    'field_default' => $column['default'] ? 'as_defined' : $column['default'],
                    'field_index' => $column['indexes'] == [] ? null : Str::lower(current($column['indexes'])['type']),
                    'field_attribute' => $column['unsigned'] ? 'unsigned' : null,
                    'as_defined' => $column['default'] ?? null,
                ];
            }, $columns);

            $this->file_name = $this->file_generator->generateBDOMigrationFile($request->table, 'drop', $rows);

            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/',
            ]);

            switch ($exitCode) {
                case 0:
                    return ApiResponse::success(__('badaso::validation.database.migration_dropped', ['table' => $request->table]));
                    break;
                default:
                    if (isset($this->file_name)) {
                        $this->file_generator->deleteMigrationFiles($this->file_name);
                    }

                    return ApiResponse::failed(__('badaso::validation.database.migration_failed'));
            }
        } catch (Exception $e) {
            if (isset($this->file_name)) {
                $this->file_generator->deleteMigrationFiles($this->file_name);
            }

            return ApiResponse::failed($e);
        }
    }

    public function rollback(Request $request)
    {
        try {
            $request->validate([
                'step' => [
                    'required',
                    'numeric',
                ],
            ]);

            $exitCode = Artisan::call('migrate:rollback', [
                '--path' => 'database/migrations/badaso/',
                '--step' => $request->step,
            ]);

            switch ($exitCode) {
                case 0:
                    return ApiResponse::success(__('badaso::validation.database.rollback_success'));
                    break;
                default:
                    return ApiResponse::failed(__('badaso::validation.database.rollback_failed'));
            }
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseMigration()
    {
        try {
            $migration = Migration::all();

            return ApiResponse::success($migration->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function checkMigrateStatus()
    {
        try {
            $migration = Migration::all()->toArray();

            $badaso_db_folder_path = database_path('migrations/badaso/*.php');
            $badaso_file = glob($badaso_db_folder_path);
            $not_migrated_migration = [];
            $check = [];

            $file_name = [];
            foreach ($badaso_file as $name) {
                $file_name[] = str_replace('.php', '', basename($name));
            }

            foreach ($migration as $key => $value) {
                $check[] = $value['migration'];
            }

            $not_migrated_migration = array_diff($file_name, $check);

            return ApiResponse::success(['data' => $not_migrated_migration, 'notMigrated' => !empty($not_migrated_migration)]);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function migrate()
    {
        try {
            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/',
            ]);

            switch ($exitCode) {
                case 0:
                    return ApiResponse::success(__('badaso::validation.database.migration_success'));
                    break;
                default:
                    return ApiResponse::failed(__('badaso::validation.database.migration_failed'));
            }
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function deleteMigration(Request $request)
    {
        try {
            $request->validate([
                'file_name' => 'required|array',
            ]);

            foreach ($request->file_name as $key => $value) {
                $path = database_path('migrations/badaso/').$value.'.php';
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            return ApiResponse::success(__('badaso::validation.database.migration_deleted'));
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function getDbmsFieldType() {
        try {
            return Badaso::getBadasoDbmsFieldType();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
