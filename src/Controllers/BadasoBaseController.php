<?php

namespace Uasoft\Badaso\Controllers;

use Illuminate\Http\Request;

class BadasoBaseController extends Controller
{
    public function browse(Request $request)
    {
        $slug = $this->getSlug($request);

        return response()->json([
            'data_type' => $this->getDataType($slug),
            'slug' => $slug,
            'data' => $this->getData($slug),
        ]);
    }

    public function read(Request $request)
    {
    }

    public function edit(Request $request)
    {
    }

    public function add(Request $request)
    {
    }

    public function delete(Request $request)
    {
    }
}
