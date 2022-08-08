<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\Firebase\FCMNotification;
use Uasoft\Badaso\Helpers\GetData;
use Uasoft\Badaso\Models\DataType;

class BadasoBaseController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $only_data_soft_delete = $request->showSoftDelete == 'true';

            $data = $this->getDataList($slug, $request->all(), $only_data_soft_delete);

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function all(Request $request)
    {
        try {
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $builder_params = [
                'order_field' => isset($request['order_field']) ? $request['order_field'] : $data_type->order_column,
                'order_direction' => isset($request['order_direction']) ? $request['order_direction'] : $data_type->order_direction,
            ];

            if ($data_type->model_name) {
                $records = GetData::clientSideWithModel($data_type, $builder_params);
            } else {
                $records = GetData::clientSideWithQueryBuilder($data_type, $builder_params);
            }

            return ApiResponse::onlyEntity($records);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $request->validate([
                'id' => 'exists:'.$data_type->name,
            ]);

            $data = [];

            $data = $this->getDataDetail($slug, $request->id);

            // add event notification handle
            $table_name = $data_type->name;
            FCMNotification::notification(FCMNotification::$ACTIVE_EVENT_ON_READ, $table_name);

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'data' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $slug = $this->getSlug($request);
                        $data_type = $this->getDataType($slug);
                        $table_entity = DB::table($data_type->name)->where('id', $request->data['id'])->first();
                        if (is_null($table_entity)) {
                            $fail(__('badaso::validation.crud.id_not_exist'));
                        }
                    },
                ],
            ]);

            // get slug by route name and get data type
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            // get data in request, validate, and update data
            $data = $request->input('data');
            $this->validateData($data, $data_type);
            $updated = $this->updateData($data, $data_type);

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            DB::commit();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties([
                    'old' => $updated['old_data'],
                    'attributes' => $updated['updated_data'],
                ])
                ->log($data_type->display_name_singular.' has been updated');

            // add event notification handle
            $table_name = $data_type->name;
            FCMNotification::notification(FCMNotification::$ACTIVE_EVENT_ON_UPDATE, $table_name);

            return ApiResponse::onlyEntity($updated['updated_data']);
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
                'data' => [
                    'required',
                ],
            ]);

            // get slug by route name and get data type in table
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            // get data from request
            $data = $request->input('data');

            // validate and store data to table
            $this->validateData($data, $data_type);
            $stored_data = $this->insertData($data, $data_type);

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties(['attributes' => $stored_data])
                ->log($data_type->display_name_singular.' has been created');

            DB::commit();

            // add event notification handle
            $table_name = $data_type->name;
            FCMNotification::notification(FCMNotification::$ACTIVE_EVENT_ON_CREATE, $table_name);

            return ApiResponse::onlyEntity($stored_data);
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
                'slug' => 'required',
                'data' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $slug = $this->getSlug($request);
                        $data_type = $this->getDataType($slug);
                        $table_entity = DB::table($data_type->name)->where('id', $request->data[0]['value'])->first();

                        if (is_null($table_entity)) {
                            $fail(__('badaso::validation.crud.id_not_exist'));
                        }
                    },
                ],
                'data.*.field' => ['required'],
                'data.*.value' => ['required'],
            ]);

            $is_hard_delete = $request->isHardDelete == 'true';

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $data = $this->createDataFromRaw($request->input('data') ?? [], $data_type);

            $this->deleteData($data, $data_type, $is_hard_delete);

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties($data)
                ->log($data_type->display_name_singular.' has been deleted');

            DB::commit();

            // add event notification handle
            $table_name = $data_type->name;
            FCMNotification::notification(FCMNotification::$ACTIVE_EVENT_ON_DELETE, $table_name);

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function restore(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'slug' => 'required',
                'data' => [
                    'required',
                ],
                'data.*.field' => ['required'],
                'data.*.value' => ['required'],
            ]);

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $data = $this->createDataFromRaw($request->input('data') ?? [], $data_type);
            $this->restoreData($data, $data_type);

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties($data)
                ->log($data_type->display_name_singular.' has been restore');

            DB::commit();

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMultiple(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'slug' => 'required',
                'data' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $slug = $this->getSlug($request);
                        $data_type = $this->getDataType($slug);

                        $data = $this->createDataFromRaw($request->input('data') ?? [], $data_type);
                        $ids = $data['ids'];
                        $id_list = explode(',', $ids);
                        foreach ($id_list as $id) {
                            $table_entity = DB::table($data_type->name)->where('id', $id)->first();
                            if (is_null($table_entity)) {
                                $fail(__('badaso::validation.crud.id_not_exist'));
                            }
                        }
                    },
                ],
                'data.*.field' => ['required'],
                'data.*.value' => ['required'],
            ]);

            $is_hard_delete = $request->isHardDelete == 'true';

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $data = $this->createDataFromRaw($request->input('data') ?? [], $data_type);
            $ids = $data['ids'];
            $id_list = explode(',', $ids);
            foreach ($id_list as $id) {
                $should_delete['id'] = $id;
                $this->deleteData($should_delete, $data_type, $is_hard_delete);
            }

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties($data)
                ->log($data_type->display_name_singular.' has been bulk deleted');

            DB::commit();

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function restoreMultiple(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'slug' => 'required',
                'data' => [
                    'required',
                ],
                'data.*.field' => ['required'],
                'data.*.value' => ['required'],
            ]);

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);

            $data = $this->createDataFromRaw($request->input('data') ?? [], $data_type);
            $ids = $data['ids'];
            $id_list = explode(',', $ids);
            foreach ($id_list as $id) {
                $should_delete['id'] = $id;
                $this->restoreData($should_delete, $data_type);
            }

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            activity($data_type->display_name_singular)
                ->causedBy($user_auth ?? null)
                ->withProperties($data)
                ->log($data_type->display_name_singular.' has been bulk deleted');

            DB::commit();

            return ApiResponse::onlyEntity($data);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function sort(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'slug' => 'required',
                'data' => [
                    'required',
                ],
            ]);

            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);
            $order_column = $data_type->order_column;

            $guard = config('badaso.authenticate.guard');
            $user_auth = Auth::guard($guard)->user();

            if ($data_type->model_name) {
                $model = app($data_type->model_name);
                foreach ($request->data as $index => $row) {
                    $single_data = $model::find($row['id']);
                    $single_data[$order_column] = $index + 1;
                    $single_data->save();

                    activity($data_type->display_name_singular)
                        ->causedBy($user_auth ?? null)
                        ->withProperties(['attributes' => $single_data])
                        ->log($data_type->display_name_singular.' has been sorted');
                }
            } else {
                foreach ($request->data as $index => $row) {
                    $updated_data[$order_column] = $index + 1;
                    DB::table($data_type->name)->where('id', $row['id'])->update($updated_data);

                    activity($data_type->display_name_singular)
                        ->causedBy($user_auth ?? null)
                        ->withProperties(['attributes' => $updated_data])
                        ->log($data_type->display_name_singular.' has been sorted');
                }
            }

            DB::commit();

            return ApiResponse::onlyEntity();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function setMaintenanceState(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'slug' => 'required|exists:Uasoft\Badaso\Models\DataType,slug',
                'is_maintenance' => 'required',
            ]);

            $data_type = DataType::where('slug', $request->slug)->firstOrFail();
            $data_type->is_maintenance = $request->is_maintenance ? 1 : 0;
            $data_type->save();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
