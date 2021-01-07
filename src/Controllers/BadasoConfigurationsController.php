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
use Uasoft\Badaso\Traits\FileHandler;

class BadasoConfigurationsController extends Controller
{
    use FileHandler;

    public function browse(Request $request)
    {
        $configurations = Configuration::all();

        $data['configurations'] = $configurations;

        return ApiResponse::success(collect($data)->toArray());
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);
            $configuration = Configuration::find($request->id);

            $data['configuration'] = $configuration;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function applyable(Request $request)
    {
        try {
            $configurations = Configuration::all();
            $configuration = [];
            foreach ($configurations as $row) {
                $configuration[$row->key] = $row->value;
            }

            $data['configuration'] = $configuration;

            return ApiResponse::success(json_decode(json_encode($data)));
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
                $configuration->key = $request->key;
                $configuration->display_name = $request->display_name;
                if (in_array($request->type, ['upload_image', 'upload_file'])) {
                    $uploaded_path = $this->handleUploadFiles([$request->value]);
                    $configuration->value = implode(',', $uploaded_path);
                } elseif (in_array($request->type, ['upload_image_multiple', 'upload_file_multiple'])) {
                    $uploaded_path = $this->handleUploadFiles($request->value);
                    $configuration->value = implode(',', $uploaded_path);
                } else {
                    $configuration->value = $request->value;
                }
                $configuration->details = $request->details;
                $configuration->type = $request->type;
                $configuration->order = $request->order;
                $configuration->group = $request->group;
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
                'group' => 'required',
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
