<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Uasoft\Badaso\Facades\Badaso;

class BadasoBreadController extends Controller
{
    public function browse(Request $request)
    {
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

        return response()->json([
            'tables' => $breads,
        ]);
    }

    public function read(Request $request)
    {
        $slug = $request->get('slug', '');
        $data_type = Badaso::model('DataType')::where('slug', $slug)->first();
        $class = new ReflectionClass(Badaso::modelClass('DataType'));
        $class_methods = $class->getMethods();
        foreach ($class_methods as $class_method) {
            if ($class_method->class == Badaso::modelClass('DataType')) {
                try {
                    $data_type[$class_method->name] = $data_type->{$class_method->name};
                } catch (Exception $e) {
                    $data_type[$class_method->name] = $data_type->{$class_method->name}();
                }
            }
        }

        return response()->json([
            'data_type' => $data_type,
        ]);
    }

    public function edit(Request $request)
    {
    }

    public function add(Request $request)
    {
    }

    public function delete(Request $request)
    {
    }
}
