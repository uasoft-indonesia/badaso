<?php

$admin_panel_route_prefix = \config('badaso.admin_panel_route_prefix');
Route::get('/'.$admin_panel_route_prefix.'/{any?}', function () {
    return view('badaso::admin-panel.index');
})->where('any', '.*');
