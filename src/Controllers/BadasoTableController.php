<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\CaseConvert;
use Uasoft\Badaso\Models\DataRow;
use Uasoft\Badaso\Models\DataType;

class BadasoTableController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $db_name = config('badaso.db_name', '');
            $tables = DB::select('SHOW TABLES');
            $key = 'Tables_in_'.$db_name;
            $tables = collect($tables);

            $tables = $tables->map(function ($table) use ($key) {
                $table->table_name = $table->{$key};
                $table->value = $table->{$key};
                $table->label = ucfirst(str_replace('_', ' ', $table->{$key}));

                return $table;
            });

            $data['tables'] = $tables;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'table' => 'required',
            ]);

            $table = $request->table;
            $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($table);
            $fields = [];
            foreach ($columns as $key => $column) {
                $fields[] = [
                    'name' => $column->getName(),
                    'type' => str_replace('\\', '', ucfirst($column->getType())),
                    'is_not_null' => $column->getNotNull(),
                    'default' => $column->getDefault(),
                    'length' => $column->getLength(),
                    'value' => $column->getName(),
                    'label' => ucfirst(str_replace('_', ' ', $column->getName())),
                ];
            }

            $data['table_fields'] = $fields;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }

    public function generateBread(Request $request)
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

    public function getDataByTable(Request $request)
    {
        try {
            $request->validate([
                'table' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!Schema::hasTable($value)) {
                            $fail(__('badaso::validation.bread.table_not_found', ['table' => $value]));
                        }
                    },
                ],
            ]);

            $records = DB::table($request->table)->select('*')->get();

            $data[$request->table] = $records;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function getRelationDataBySlug(Request $request)
    {
        try {
            $request->validate([
                'slug' => 'required|exists:data_types,slug',
            ]);
            $slug = $request->input('slug', '');
            $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
            $data_rows = $data_type->dataRows;
            $data = [];
            $relational_rows = collect($data_rows)->filter(function ($value, $key) {
                return $value->relation != null;
            })->all();

            foreach ($relational_rows as $key => $field) {
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
                    $relation_data = DB::table($destination_table)->select([
                        $destination_table_column,
                        $destination_table_display_column,
                    ])
                    ->get();
                    $result = collect($relation_data);
                    $data[$destination_table] = $result->map(function ($res) use ($destination_table_column, $destination_table_display_column) {
                        $item = $res;
                        $item->value = $res->{$destination_table_column};
                        $item->label = $res->{$destination_table_display_column};

                        return $item;
                    })->toArray();
                }
            }

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return APIResponse::failed($e);
        }
    }
}
