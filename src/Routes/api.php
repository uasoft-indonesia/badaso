<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Middleware\BadasoAuthenticate;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Controllers', 'as' => 'badaso.'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'data'], function () {
            Route::get('/components', 'BadasoDataController@getComponents');
            Route::get('/filter-operators', 'BadasoDataController@getFilterOperators');
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/login', 'BadasoAuthController@login');
            Route::post('/logout', 'BadasoAuthController@logout');
            Route::post('/register', 'BadasoAuthController@register');
            Route::post('/change-password', 'BadasoAuthController@changePassword');
            Route::post('/forgot-password', 'BadasoAuthController@forgetPassword');
            Route::post('/reset-password', 'BadasoAuthController@resetPassword');
            Route::post('/refresh-token', 'BadasoAuthController@refreshToken');
            Route::post('/verify', 'BadasoAuthController@verify');
            Route::post('/user', 'BadasoAuthController@getAuthenticatedUser');
        });
        Route::group(['prefix' => 'bread'], function () {
            Route::get('/', 'BadasoBreadController@browse');
            Route::get('/read', 'BadasoBreadController@read');
            Route::put('/edit', 'BadasoBreadController@edit');
            Route::post('/add', 'BadasoBreadController@add');
            Route::delete('/delete', 'BadasoBreadController@delete');
            Route::get('/generate', 'BadasoBreadController@generate');
        });
        Route::group(['prefix' => 'entity', 'middleware' => BadasoAuthenticate::class], function () {
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
                    Route::delete($data_type->slug.'/delete-multiple', $bread_controller.'@deleteMultiple')->name($data_type->slug.'.delete-multiple');
                }
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
            } catch (\Exception $e) {
                // do nothing, might just be because table not yet migrated.
            }
        });
        Route::group(['prefix' => 'file', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/view', 'BadasoFileController@viewFile');
            Route::get('/download', 'BadasoFileController@downloadFile');
            Route::post('/upload', 'BadasoFileController@uploadFile');
            Route::delete('/delete', 'BadasoFileController@deleteFile');
        });
        Route::group(['prefix' => 'configuration', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoConfigurationsController@browse');
            Route::get('/read', 'BadasoConfigurationsController@read');
            Route::put('/edit', 'BadasoConfigurationsController@edit');
            Route::put('/edit-multiple', 'BadasoConfigurationsController@editMultiple');
            Route::post('/add', 'BadasoConfigurationsController@add');
            Route::delete('/delete', 'BadasoConfigurationsController@delete');
        });
        Route::group(['prefix' => 'menu', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoMenuController@browseMenu');
            Route::get('/read', 'BadasoMenuController@readMenu');
            Route::put('/edit', 'BadasoMenuController@editMenu');
            Route::post('/add', 'BadasoMenuController@addMenu');
            Route::delete('/delete', 'BadasoMenuController@deleteMenu');

            Route::get('/item', 'BadasoMenuController@browseMenuItem');
            Route::get('/item-by-key', 'BadasoMenuController@browseMenuItemByKey');
            Route::get('/item/read', 'BadasoMenuController@readMenuItem');
            Route::put('/item/edit', 'BadasoMenuController@editMenuItem');
            Route::put('/item/edit-order', 'BadasoMenuController@editMenuItemOrder');
            Route::post('/item/add', 'BadasoMenuController@addMenuItem');
            Route::delete('/item/delete', 'BadasoMenuController@deleteMenuItem');
        });
    });
});
