<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Database\Schema\SchemaManager;
use Uasoft\Badaso\Helpers\MigrationParser;
use Uasoft\Badaso\ContentManager\FileGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BadasoDatabaseController extends Controller
{
    /** @var FileGenerator */
    private $file_generator;

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
            ]);

            $this->file_generator->generateBDOMigrationFile($request->table, 'create', $request->rows);

            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/'
            ]);

            switch ($exitCode) {
                case 0:
                    $msg = __('badaso::validation.database.migration_created');
                    return ApiResponse::success($msg);
                    break;
                case 1:
                case 2:
                    $this->file_generator->deleteMigrationFiles($request->table, 'create');
                    return ApiResponse::failed($e);
            }

        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }

    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'table' => [
                    'required',
                    'unique:data_types,slug',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.database.table_not_found', ['table' => $value]));
                        }
                    },
                    Rule::notIn(Badaso::getProtectedTables()),
                ],
            ]);

            $columns = SchemaManager::describeTable($request->table);

            return ApiResponse::success($columns);
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
                $this->file_generator->generateBDOAlterMigrationFile($table, collect($fields['modified_fields'])->sortBy('modify_type')->reverse()->toArray(), 'alter');
            }

            $exitCode = Artisan::call('migrate', [
                '--path' => 'database/migrations/badaso/'
            ]);

            dd($exitCode);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
