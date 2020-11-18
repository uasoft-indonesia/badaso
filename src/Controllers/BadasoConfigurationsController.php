<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Configuration;

class BadasoConfigurationsController extends Controller
{
    public function browse(Request $request)
    {
        $configurations = Configuration::all();

        return ApiResponse::success(collect($configurations)->toArray());
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);
            $configuration = Configuration::find($request->id);

            return ApiResponse::success($configuration);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required',
            ]);
            $configuration = Configuration::find($request->id);

            if (!is_null($configuration)) {
                $data = $request->all();
                $configuration_fillable = $configuration->getFillable();
                foreach ($data as $key => $value) {
                    $property = Str::snake($key);
                    if (in_array($property, $configuration_fillable)) {
                        $configuration->{$property} = $value;
                    }
                }

                $configuration->save();
            }

            DB::commit();

            return ApiResponse::success($configuration);
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }

    public function editMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'configurations' => 'required',
            ]);
            foreach ($request->configurations as $configuration) {
                Validator::make($configuration, [
                    'id' => ['required'],
                ])->validate();
                $updated_configuration = Configuration::find($configuration['id']);
                if (!is_null($configuration)) {
                    $data = $configuration;
                    $configuration_fillable = $updated_configuration->getFillable();
                    foreach ($data as $key => $value) {
                        $property = Str::snake($key);
                        if (in_array($property, $configuration_fillable)) {
                            $updated_configuration->{$property} = $value;
                        }
                    }
                    $updated_configuration->save();
                }
            }

            DB::commit();

            return ApiResponse::success();
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
                'key' => 'required|unique:configurations',
                'display_name' => 'required',
                'value' => 'required',
                'type' => 'required',
            ]);
            $configuration = new Configuration();
            $data = $request->all();
            $configuration_fillable = $configuration->getFillable();
            foreach ($data as $key => $value) {
                $property = Str::snake($key);
                if (in_array($property, $configuration_fillable)) {
                    $configuration->{$property} = $value;
                }
            }

            $configuration->save();

            DB::commit();

            return ApiResponse::success($configuration);
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

            Configuration::find($request->id)->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
