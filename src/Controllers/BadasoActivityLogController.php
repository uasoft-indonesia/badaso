<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Spatie\Activitylog\Models\Activity;

class BadasoActivityLogController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $activitylog = Activity::orderBy('created_at', 'DESC')->get();

            $data['activitylog'] = $activitylog;

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
