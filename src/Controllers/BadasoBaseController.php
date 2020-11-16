<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoBaseController extends Controller
{
    public function browse(Request $request)
    {
        try {
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);
            $data = $this->getDataList($slug);

            return ApiResponse::entity($data_type, $data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);
            $data = $this->getDataDetail($slug, $request->id);

            return ApiResponse::entity($data_type, $data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
    }

    public function add(Request $request)
    {
        try {
            $slug = $this->getSlug($request);
            $data_type = $this->getDataType($slug);
            $data = $this->createDataFromRaw($request->input('data') ?? []);
            $this->validateData($data, $data_type);
            // $stored_data = $this->insertData($data, $data_type);

            return ApiResponse::entity($data_type, null);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
    }
}
