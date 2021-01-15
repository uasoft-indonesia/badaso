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
        $component_list = collect($components)->map(function ($component) {
            return [
                'value' => $component,
                'label' => ucfirst(str_replace('_', ' ', $component)),
            ];
        })->toArray();

        $data['components'] = $component_list;

        return ApiResponse::success($data);
    }

    public function getFilterOperators(Request $request)
    {
        $operators = Badaso::getFilterOperator();

        return ApiResponse::success($operators);
    }

    public function getSupportedTableRelations()
    {
        $table_relations = Badaso::getSupportedTableRelations();
        $table_relations = collect($table_relations)->map(function ($table_relation) {
            return [
                'value' => $table_relation,
                'label' => ucfirst(str_replace('_', ' ', $table_relation)),
            ];
        })->toArray();

        $data['table_relations'] = $table_relations;

        return ApiResponse::success($data);
    }

    public function getConfigurationGroups()
    {
        $groups = config('badaso.configuration_groups') ?? [];

        $data['groups'] = $groups;

        return ApiResponse::success($data);
    }
}
