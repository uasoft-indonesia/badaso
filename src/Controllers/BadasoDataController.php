<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoDataController extends Controller
{
    public function getComponents(Request $request)
    {
        $components = Badaso::getComponents();

        return ApiResponse::success($components);
    }

    public function getFilterOperators(Request $request)
    {
        $operators = Badaso::getFilterOperator();

        return ApiResponse::success($operators);
    }
}
