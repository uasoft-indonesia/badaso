<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoActivityLogController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $filter = '%'.$request->get('filter', '').'%';
            $order_field = $request->get('order_field');
            $order_direction = $request->get('order_direction');

            $activitylog_query = Activity::query()
                ->where('log_name', 'LIKE', $filter)
                ->orWhere('description', 'LIKE', $filter)
                ->orWhere('subject_id', 'LIKE', $filter)
                ->orWhere('subject_type', 'LIKE', $filter)
                ->orWhere('causer_id', 'LIKE', $filter)
                ->orWhere('causer_type', 'LIKE', $filter)
                ->orWhere('properties', 'LIKE', $filter);

            if ($order_field) {
                $order_field = Str::snake($order_field);
                $order_direction = $order_direction ? Str::snake($order_direction) : null;
                $activitylog_query = $activitylog_query->orderBy($order_field, $order_direction);
            } else {
                $activitylog_query = $activitylog_query->orderBy('created_at', 'desc');
            }

            $activitylog = $activitylog_query->paginate($limit);

            $data = json_decode(json_encode($activitylog));
            $data->activitylog = $activitylog->getCollection();

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:activity_log,id',
            ]);

            $activitylog = Activity::find($request->id);

            $data['activitylog'] = $activitylog;
            $data['subject'] = $activitylog->subject;
            $data['causer'] = $activitylog->causer;
            $data['properties'] = $activitylog->properties->toArray();

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
