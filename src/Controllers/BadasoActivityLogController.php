<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoActivityLogController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $filter = '%'.$request->get('filter', '').'%';

            $activitylog = Activity::orderBy('created_at', 'DESC')
                ->where('log_name', 'LIKE', $filter)
                ->orWhere('description', 'LIKE', $filter)
                ->orWhere('subject_id', 'LIKE', $filter)
                ->orWhere('subject_type', 'LIKE', $filter)
                ->orWhere('causer_id', 'LIKE', $filter)
                ->orWhere('causer_type', 'LIKE', $filter)
                ->orWhere('properties', 'LIKE', $filter)
                ->paginate($limit);

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
