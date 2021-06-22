<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\ApiDocs;
use Uasoft\Badaso\Models\DataType;

class GenerateForSwagger
{
    public function handle($request, Closure $next)
    {
        switch ($request->getPathInfo()) {
            case '/api-docs':
                $data_types = DataType::all();
                foreach ($data_types as $key => $data_type) {
                    $data_rows = $data_type->dataRows;
                    $table_name = $data_type->name;

                    ApiDocs::generateAPIDocs($table_name, $data_rows, $data_type);
                }
                break;
        }

        return $next($request);
    }
}
