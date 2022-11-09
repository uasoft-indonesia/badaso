<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Middleware\ApiRequest;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Controllers', 'as' => 'badaso.', 'middleware' => [ApiRequest::class]], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'BadasoDashboardController@index')->middleware(\config('badaso.middleware.authenticate'));
        });

        Route::group(['prefix' => 'data'], function () {
            Route::get('/components', 'BadasoDataController@getComponents');
            Route::get('/filter-operators', 'BadasoDataController@getFilterOperators');
            Route::get('/table-relations', 'BadasoDataController@getSupportedTableRelations');
            Route::get('/configuration-groups', 'BadasoDataController@getConfigurationGroups');
        });

        Route::group(['prefix' => 'activitylogs'], function () {
            Route::get('/', 'BadasoActivityLogController@browse')->middleware(config('badaso.middleware.check_permission').':browse_activitylogs');
            Route::get('/read', 'BadasoActivityLogController@read')->middleware(config('badaso.middleware.check_permission').':read_activitylogs');
        });

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/login', 'BadasoAuthController@login');
            Route::post('/logout', 'BadasoAuthController@logout');
            Route::post('/register', 'BadasoAuthController@register');
            Route::post('/forgot-password', 'BadasoAuthController@forgetPassword');
            Route::post('/forgot-password-verify', 'BadasoAuthController@validateTokenForgetPassword');
            Route::post('/reset-password', 'BadasoAuthController@resetPassword');
            Route::post('/refresh-token', 'BadasoAuthController@refreshToken');
            Route::post('/verify', 'BadasoAuthController@verify');
            Route::post('/re-request-verification', 'BadasoAuthController@reRequestVerification');
            Route::post('/'.env('MIX_BADASO_SECRET_LOGIN_PREFIX'), 'BadasoAuthController@secretLogin');
        });

        Route::group(['prefix' => 'auth/user'], function () {
            Route::get('/', 'BadasoAuthController@getAuthenticatedUser');
            Route::put('/change-password', 'BadasoAuthController@changePassword');
            Route::put('/profile', 'BadasoAuthController@updateProfile');
            Route::put('/email', 'BadasoAuthController@updateEmail');
            Route::post('/verify-email', 'BadasoAuthController@verifyEmail');
        });

        Route::group(['prefix' => 'file'], function () {
            Route::get('/view', 'BadasoFileController@viewFile');
            Route::get('/download', 'BadasoFileController@downloadFile');
            Route::post('/upload', 'BadasoFileController@uploadFile')->middleware(config('badaso.middleware.check_permission').':upload_file');
            Route::delete('/delete', 'BadasoFileController@deleteFile');
            Route::get('/browse/lfm', 'BadasoFileController@browseFileUsingLfm');
            Route::post('/upload/lfm', 'BadasoFileController@uploadFileUsingLfm');
            Route::get('/delete/lfm', 'BadasoFileController@deleteFileUsingLfm');
            Route::get('/mimetypes', 'BadasoFileController@availableMimetype');
        });

        Route::group(['prefix' => 'configurations'], function () {
            Route::get('/applyable', 'BadasoConfigurationsController@applyable');
            Route::get('/maintenance', 'BadasoConfigurationsController@isMaintenance');

            Route::get('/', 'BadasoConfigurationsController@browse')->middleware(config('badaso.middleware.check_permission').':browse_configurations');
            Route::get('/read', 'BadasoConfigurationsController@read')->middleware(config('badaso.middleware.check_permission').':read_configurations');
            Route::get('/fetch', 'BadasoConfigurationsController@fetch');
            Route::get('/fetch-multiple', 'BadasoConfigurationsController@fetchMultiple');
            Route::put('/edit', 'BadasoConfigurationsController@edit')->middleware(config('badaso.middleware.check_permission').':edit_configurations');
            Route::put('/edit-multiple', 'BadasoConfigurationsController@editMultiple')->middleware(config('badaso.middleware.check_permission').':edit_configurations');
            Route::post('/add', 'BadasoConfigurationsController@add')->middleware(config('badaso.middleware.check_permission').':add_configurations');
            Route::delete('/delete', 'BadasoConfigurationsController@delete')->middleware(config('badaso.middleware.check_permission').':delete_configurations');
        });

        Route::group(['prefix' => 'menus'], function () {
            Route::get('/', 'BadasoMenuController@browseMenu')->middleware(config('badaso.middleware.check_permission').':browse_menus');
            Route::get('/read', 'BadasoMenuController@readMenu')->middleware(config('badaso.middleware.check_permission').':read_menus');
            Route::put('/edit', 'BadasoMenuController@editMenu')->middleware(config('badaso.middleware.check_permission').':edit_menus');
            Route::post('/add', 'BadasoMenuController@addMenu')->middleware(config('badaso.middleware.check_permission').':add_menus');
            Route::delete('/delete', 'BadasoMenuController@deleteMenu')->middleware(config('badaso.middleware.check_permission').':delete_menus');
            Route::put('/arrange-items', 'BadasoMenuController@editMenuItemsOrder')->middleware(config('badaso.middleware.check_permission').':edit_menus');

            Route::get('/item', 'BadasoMenuController@browseMenuItem')->middleware(config('badaso.middleware.check_permission').':browse_menu_items');
            Route::get('/item/read', 'BadasoMenuController@readMenuItem')->middleware(config('badaso.middleware.check_permission').':read_menu_items');
            Route::put('/item/edit', 'BadasoMenuController@editMenuItem')->middleware(config('badaso.middleware.check_permission').':edit_menu_items');
            Route::put('/item/edit-order', 'BadasoMenuController@editMenuItemOrder')->middleware(config('badaso.middleware.check_permission').':edit_menu_items');
            Route::post('/item/add', 'BadasoMenuController@addMenuItem')->middleware(config('badaso.middleware.check_permission').':add_menu_items');
            Route::delete('/item/delete', 'BadasoMenuController@deleteMenuItem')->middleware(config('badaso.middleware.check_permission').':delete_menu_items');
            Route::get('/item/permissions', 'BadasoMenuController@getMenuItemPermissions')->middleware(config('badaso.middleware.check_permission').':edit_menu_items');
            Route::put('/item/permissions', 'BadasoMenuController@setMenuItemPermissions')->middleware(config('badaso.middleware.check_permission').':edit_menu_items');

            Route::get('/item-by-key', 'BadasoMenuController@browseMenuItemByKey');
            Route::get('/item-by-keys', 'BadasoMenuController@browseMenuItemByKeys');

            Route::put('/menu-options', 'BadasoMenuController@menuOptions');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'BadasoUserController@browse')->middleware(config('badaso.middleware.check_permission').':browse_users');
            Route::get('/read', 'BadasoUserController@read')->middleware(config('badaso.middleware.check_permission').':read_users');
            Route::put('/edit', 'BadasoUserController@edit')->middleware(config('badaso.middleware.check_permission').':edit_users');
            Route::post('/add', 'BadasoUserController@add')->middleware(config('badaso.middleware.check_permission').':add_users');
            Route::delete('/delete', 'BadasoUserController@delete')->middleware(config('badaso.middleware.check_permission').':delete_users');
            Route::delete('/delete-multiple', 'BadasoUserController@deleteMultiple')->middleware(config('badaso.middleware.check_permission').':delete_users');
        });

        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', 'BadasoPermissionController@browse')->middleware(config('badaso.middleware.check_permission').':browse_permissions');
            Route::get('/read', 'BadasoPermissionController@read')->middleware(config('badaso.middleware.check_permission').':read_permissions');
            Route::put('/edit', 'BadasoPermissionController@edit')->middleware(config('badaso.middleware.check_permission').':edit_permissions');
            Route::post('/add', 'BadasoPermissionController@add')->middleware(config('badaso.middleware.check_permission').':add_permissions');
            Route::delete('/delete', 'BadasoPermissionController@delete')->middleware(config('badaso.middleware.check_permission').':delete_permissions');
            Route::delete('/delete-multiple', 'BadasoPermissionController@deleteMultiple')->middleware(config('badaso.middleware.check_permission').':delete_permissions');
        });

        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', 'BadasoRoleController@browse')->middleware(config('badaso.middleware.check_permission').':browse_roles');
            Route::get('/read', 'BadasoRoleController@read')->middleware(config('badaso.middleware.check_permission').':read_roles');
            Route::put('/edit', 'BadasoRoleController@edit')->middleware(config('badaso.middleware.check_permission').':edit_roles');
            Route::post('/add', 'BadasoRoleController@add')->middleware(config('badaso.middleware.check_permission').':add_roles');
            Route::delete('/delete', 'BadasoRoleController@delete')->middleware(config('badaso.middleware.check_permission').':delete_roles');
            Route::delete('/delete-multiple', 'BadasoRoleController@deleteMultiple')->middleware(config('badaso.middleware.check_permission').':delete_roles');
        });

        Route::group(['prefix' => 'user-roles'], function () {
            Route::get('/', 'BadasoUserRoleController@browseByUser')->middleware(config('badaso.middleware.check_permission').':browse_user_role');
            Route::get('/all', 'BadasoUserRoleController@browse')->middleware(config('badaso.middleware.check_permission').':browse_user_role');
            Route::post('/add-edit', 'BadasoUserRoleController@addOrEdit')->middleware(config('badaso.middleware.check_permission').':add_or_edit_user_role');
            Route::get('/all-role', 'BadasoUserRoleController@browseAllRole')->middleware(config('badaso.middleware.check_permission').':browse_user_role');
        });

        Route::group(['prefix' => 'role-permissions'], function () {
            Route::get('/', 'BadasoRolePermissionController@browseByRole')->middleware(config('badaso.middleware.check_permission').':browse_role_permission');
            Route::get('/all', 'BadasoRolePermissionController@browse')->middleware(config('badaso.middleware.check_permission').':browse_role_permission');
            Route::post('/add-edit', 'BadasoRolePermissionController@addOrEdit')->middleware(config('badaso.middleware.check_permission').':add_or_edit_role_permission');
            Route::get('/all-permission', 'BadasoRolePermissionController@browseAllPermission')->middleware(config('badaso.middleware.check_permission').':browse_role_permission');
        });

        Route::group(['prefix' => 'crud'], function () {
            Route::get('/', 'BadasoCRUDController@browse')->middleware(config('badaso.middleware.check_permission').':browse_crud_data');
            Route::get('/read', 'BadasoCRUDController@read')->middleware(config('badaso.middleware.check_permission').':read_crud_data');
            Route::put('/edit', 'BadasoCRUDController@edit')->middleware(config('badaso.middleware.check_permission').':edit_crud_data');
            Route::post('/add', 'BadasoCRUDController@add')->middleware(config('badaso.middleware.check_permission').':add_crud_data');
            Route::delete('/delete', 'BadasoCRUDController@delete')->middleware(config('badaso.middleware.check_permission').':delete_crud_data');
            Route::get('/read-by-slug', 'BadasoCRUDController@readBySlug')->middleware(config('badaso.middleware.check_permission').':read_crud_data');
            Route::get('/maintenance', 'BadasoCRUDController@setMaintenanceState')->middleware(config('badaso.middleware.check_permission').':maintenance_crud_data');
        });

        Route::group(['prefix' => 'table'], function () {
            Route::get('/data-type', 'BadasoTableController@getDataType')->middleware(config('badaso.middleware.authenticate'));
            Route::get('/', 'BadasoTableController@browse')->middleware(config('badaso.middleware.authenticate'));
            Route::get('/read', 'BadasoTableController@read')->middleware(config('badaso.middleware.authenticate'));
            Route::get('/data', 'BadasoTableController@getDataByTable')->middleware(config('badaso.middleware.authenticate'));
            Route::get('/generate-crud', 'BadasoTableController@generateCRUD')->middleware(config('badaso.middleware.authenticate'));
            Route::get('/relation-data-by-slug', 'BadasoTableController@getRelationDataBySlug')->middleware(config('badaso.middleware.authenticate'));
        });

        Route::group(['prefix' => 'maintenance'], function () {
            Route::post('/', 'BadasoMaintenanceController@isMaintenance');
        });

        Route::group(['prefix' => 'entities'], function () {
            try {
                foreach (Badaso::model('DataType')::all() as $data_type) {
                    $crud_data_controller = $data_type->controller
                        ? Str::start($data_type->controller, '\\')
                        : 'BadasoBaseController';
                    Route::get($data_type->slug, $crud_data_controller.'@browse')
                        ->name($data_type->slug.'.browse')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',browse');

                    Route::get($data_type->slug.'/read', $crud_data_controller.'@read')
                        ->name($data_type->slug.'.read')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',read');

                    Route::put($data_type->slug.'/edit', $crud_data_controller.'@edit')
                        ->name($data_type->slug.'.edit')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',edit');

                    Route::post($data_type->slug.'/add', $crud_data_controller.'@add')
                        ->name($data_type->slug.'.add')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',add');

                    Route::delete($data_type->slug.'/delete', $crud_data_controller.'@delete')
                        ->name($data_type->slug.'.delete')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',delete');

                    Route::delete($data_type->slug.'/restore', $crud_data_controller.'@restore')
                        ->name($data_type->slug.'.restore')->middleware(config('badaso.middleware.authenticate'));

                    Route::delete($data_type->slug.'/delete-multiple', $crud_data_controller.'@deleteMultiple')
                        ->name($data_type->slug.'.delete-multiple')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',delete');

                    Route::delete($data_type->slug.'/restore-multiple', $crud_data_controller.'@restoreMultiple')
                        ->name($data_type->slug.'.restore-multiple')->middleware(config('badaso.middleware.authenticate'));

                    Route::put($data_type->slug.'/sort', $crud_data_controller.'@sort')
                        ->name($data_type->slug.'.sort')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',edit');

                    Route::get($data_type->slug.'/all', $crud_data_controller.'@all')
                        ->name($data_type->slug.'.all')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',edit');

                    Route::post($data_type->slug.'/maintenance', $crud_data_controller.'@setMaintenanceState')
                        ->name($data_type->slug.'.maintenance')
                        ->middleware(config('badaso.middleware.check_crud_permission').':'.$data_type->slug.',maintenance');
                }
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
            } catch (\Exception $e) {
                // do nothing, might just be because table not yet migrated.
            }
        });

        Route::group(['prefix' => 'database'], function () {
            Route::get('/', 'BadasoDatabaseController@browse')->middleware(config('badaso.middleware.check_permission').':browse_database');
            Route::get('/read', 'BadasoDatabaseController@read')->middleware(config('badaso.middleware.check_permission').':read_database');
            Route::put('/edit', 'BadasoDatabaseController@edit')->middleware(config('badaso.middleware.check_permission').':edit_database');
            Route::post('/add', 'BadasoDatabaseController@add')->middleware(config('badaso.middleware.check_permission').':add_database');
            Route::delete('/delete', 'BadasoDatabaseController@delete')->middleware(config('badaso.middleware.check_permission').':delete_database');
            Route::post('/rollback', 'BadasoDatabaseController@rollback')->middleware(config('badaso.middleware.check_permission').':rollback_database');
            Route::get('/migration/browse', 'BadasoDatabaseController@browseMigration')->middleware(config('badaso.middleware.check_permission').':browse_migration');
            Route::get('/migration/status', 'BadasoDatabaseController@checkMigrateStatus');
            Route::get('/migration/migrate', 'BadasoDatabaseController@migrate')->middleware(config('badaso.middleware.check_permission').':migrate_database');
            Route::post('/migration/delete', 'BadasoDatabaseController@deleteMigration')->middleware(config('badaso.middleware.check_permission').':delete_migration');
            Route::get('/type', 'BadasoDatabaseController@getDbmsFieldType')->middleware([config('badaso.middleware.check_permission').':add_database', config('badaso.middleware.check_permission').':edit_database']);
        });

        Route::group(['prefix' => 'firebase', 'middleware' => \config('badaso.middleware.authenticate')], function () {
            Route::group(['prefix' => 'cloud_messages'], function () {
                Route::put('/save-token-messages', 'BadasoFCMController@saveTokenMessage');
            });
            Route::group(['prefix' => 'messages', 'middleware' => \config('badaso.middleware.authenticate')], function () {
                Route::get('/', 'BadasoNotificationsController@getMessages');
                Route::put('/{id}', 'BadasoNotificationsController@readMessage');
                Route::get('/count-unread', 'BadasoNotificationsController@getCountUnreadMessage');
            });
        });
    });
});
