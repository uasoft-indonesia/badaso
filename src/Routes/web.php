<?php

$dashboard_route_prefix = \config('badaso.dashboard_route_prefix');
Route::get('/'.$dashboard_route_prefix.'/{any}', function () {
    return view('Badaso::dashboard.index');
})->where('any', '.*');
