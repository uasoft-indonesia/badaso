<?php

$prefix_file_manager_route = 'filemanager';
$middleware_file_manager = \config('lfm.middleware');

Route::group(['prefix' => $prefix_file_manager_route, 'middleware' => $middleware_file_manager], function () {
    if (class_exists("\UniSharp\LaravelFilemanager\Lfm")) {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    }
});

$admin_panel_route_prefix = \config('badaso.admin_panel_route_prefix');
Route::get('/'.$admin_panel_route_prefix.'/{any?}', function () {
    return view('badaso::admin-panel.index');
})->where('any', '.*');
