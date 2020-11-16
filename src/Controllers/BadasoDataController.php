<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uasoft\Badaso\Facades\Badaso;

class BadasoDataController extends Controller
{
    public function getComponents(Request $request)
    {
        $components = Badaso::getComponents();

        return response()->json([
            'components' => $components,
        ]);
    }
}
