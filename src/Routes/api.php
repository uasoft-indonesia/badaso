<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Controllers', 'as' => 'badaso.'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'data'], function () {
            Route::get('/components', 'BadasoDataController@getComponents');
        });
        Route::group(['prefix' => 'bread'], function () {
            Route::get('/', 'BadasoBreadController@browse');
            Route::get('/read', 'BadasoBreadController@read');
            Route::put('/edit', 'BadasoBreadController@edit');
            Route::post('/add', 'BadasoBreadController@add');
            Route::delete('/delete', 'BadasoBreadController@delete');
        });
        Route::group(['prefix' => 'entity'], function () {
            try {
                foreach (Badaso::model('DataType')::all() as $data_type) {
                    $bread_controller = $data_type->controller
                                     ? Str::start($data_type->controller, '\\')
                                     : 'BadasoBaseController';
                    Route::get($data_type->slug, $bread_controller.'@browse')->name($data_type->slug.'.browse');
                    Route::get($data_type->slug.'/read', $bread_controller.'@read')->name($data_type->slug.'.read');
                    Route::put($data_type->slug.'/edit', $bread_controller.'@edit')->name($data_type->slug.'.edit');
                    Route::post($data_type->slug.'/add', $bread_controller.'@add')->name($data_type->slug.'.add');
                    Route::delete($data_type->slug.'/delete', $bread_controller.'@delete')->name($data_type->slug.'.delete');
                }
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
            } catch (\Exception $e) {
                // do nothing, might just be because table not yet migrated.
            }
        });
    });
});
