<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\Redis\ConfigurationRedis;
use Uasoft\Badaso\Models\Configuration;
use Uasoft\Badaso\Traits\FileHandler;

class BadasoConfigurationsController extends Controller
{
    use FileHandler;

    public function browse(Request $request)
    {
        $configurations = Configuration::orderBy('order')->get();

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

    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'key' => 'sometimes|required|exists:Uasoft\Badaso\Models\Configuration,key',
                'group' => 'sometimes|required|exists:Uasoft\Badaso\Models\Configuration,group',
            ]);

            $configuration = Configuration::when($request->key, function ($query, $key) {
                $query->orWhere('key', $key);
            })
                ->when($request->group, function ($query, $group) {
                    return $query->orWhere('group', $group);
                })
                ->get()
                ->toArray();

            $data['configuration'] = $configuration;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function fetchMultiple(Request $request)
    {
        try {
            $request->validate([
                'key' => 'sometimes|required',
                'group' => 'sometimes|required',
            ]);

            $configuration = Configuration::when($request->key, function ($query, $key) {
                foreach (explode(',', $key) as $index => $value) {
                    $query->orWhere('key', $value);
                }

                return $query;
            })
                ->when($request->group, function ($query, $group) {
                    foreach (explode(',', $group) as $index => $value) {
                        $query->orWhere('group', $value);
                    }

                    return $query;
                })
                ->get()
                ->toArray();

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

            if (! is_null($configuration)) {
                $configuration->key = $request->key;
                $configuration->display_name = $request->display_name;
                $configuration->value = $request->value;
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
                if (! is_null($configuration)) {
                    $updated_configuration->key = $configuration['key'];
                    $updated_configuration->display_name = $configuration['display_name'];
                    $updated_configuration->value = $configuration['value'];
                    $updated_configuration->details = json_encode($configuration['details']);
                    $updated_configuration->type = $configuration['type'];
                    $updated_configuration->order = $configuration['order'];
                    $updated_configuration->group = $configuration['group'];
                    $updated_configuration->save();
                }
            }

            // save configurations to redis
            ConfigurationRedis::save(Configuration::all());

            DB::commit();
            activity('Configurations')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->configurations])
                ->performedOn($updated_configuration)
                ->event('updated')
                ->log('Configuration has been updated');

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
                'key' => 'required|unique:Uasoft\Badaso\Models\Configuration',
                'display_name' => 'required',
                'group' => 'required',
                'type' => 'required',
                'details' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if (in_array($request->type, ['checkbox', 'radio', 'select', 'select_multiple'])) {
                            json_decode($value);
                            $is_json = (json_last_error() == JSON_ERROR_NONE);
                            if (! $is_json) {
                                $fail('The details must be a valid JSON string.');
                            }
                        }
                    },
                    function ($attribute, $value, $fail) use ($request) {
                        if (in_array($request->type, ['checkbox', 'radio', 'select', 'select_multiple'])) {
                            if (! isset($value) || is_null($value)) {
                                $fail('Options is required for  Checkbox, Radio, Select, Select-multiple');
                            }
                        }
                    },
                ],
            ]);
            $configuration = new Configuration();
            $data = $request->all();
            $data['can_delete'] = $request->get('can_delete', true);
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

            $config = Configuration::find($request->id);
            if ($config->can_delete) {
                $config->delete();
            } else {
                throw new SingleException('Cannot delete this config');
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
