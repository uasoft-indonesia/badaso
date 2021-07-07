<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Uasoft\Badaso\Helpers\ApiDocs;
use Uasoft\Badaso\Models\DataType;

class GenerateForSwagger
{
    public function handle($request, Closure $next)
    {
        switch ($request->getPathInfo()) {
            case $this->getPath(env('MIX_API_DOCUMENTATION_ROUTE', '/api-docs')):
                $data_types = DataType::all();
                foreach ($data_types as $index => $data_type) {
                    $data_rows = $data_type->dataRows;
                    $table_name = $data_type->name;

                    ApiDocs::generateAPIDocs($table_name, $data_rows, $data_type);
                }

                Artisan::call('l5-swagger:generate');
                break;
        }

        return $next($request);
    }

    public function getPath($api_docs)
    {
        if (substr($api_docs, 0, 1) != '/') {
            $api_docs = "/{$api_docs}";
        }

        return $api_docs;
    }
}
