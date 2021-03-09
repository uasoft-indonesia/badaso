<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Interfaces\WidgetInterface;

class BadasoDashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $widgets = config('badaso.widgets');
            $data = [];
            foreach ($widgets as $widget) {
                $widget_class = new $widget();
                if ($widget_class instanceof WidgetInterface) {
                    $widget_data = $widget_class->run();
                    $data[] = $widget_data;
                }
            }

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
