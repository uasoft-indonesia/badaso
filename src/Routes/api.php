<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Middleware\ApiRequest;
use Uasoft\Badaso\Middleware\BadasoAuthenticate;
use Uasoft\Badaso\Middleware\BadasoCheckPermissions;
use Uasoft\Badaso\Middleware\BadasoCheckPermissionsForCRUD;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Controllers', 'as' => 'badaso.', 'middleware' => [ApiRequest::class]], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'BadasoDashboardController@index')->middleware(BadasoAuthenticate::class);
        });

        Route::group(['prefix' => 'data'], function () {
            Route::get('/components', 'BadasoDataController@getComponents');
            Route::get('/filter-operators', 'BadasoDataController@getFilterOperators');
            Route::get('/table-relations', 'BadasoDataController@getSupportedTableRelations');
            Route::get('/configuration-groups', 'BadasoDataController@getConfigurationGroups');
        });

        Route::group(['prefix' => 'activitylogs'], function () {
            Route::get('/', 'BadasoActivityLogController@browse')->middleware(BadasoCheckPermissions::class.':browse_activitylogs');
            Route::get('/read', 'BadasoActivityLogController@read')->middleware(BadasoCheckPermissions::class.':read_activitylogs');
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
            Route::post('/upload', 'BadasoFileController@uploadFile')->middleware(BadasoCheckPermissions::class.':upload_file');
            Route::delete('/delete', 'BadasoFileController@deleteFile');
            Route::get('/browse/lfm', 'BadasoFileController@browseFileUsingLfm');
            Route::post('/upload/lfm', 'BadasoFileController@uploadFileUsingLfm');
            Route::get('/delete/lfm', 'BadasoFileController@deleteFileUsingLfm');
            Route::get('/mimetypes', 'BadasoFileController@availableMimetype');
        });

        Route::group(['prefix' => 'configurations'], function () {
            Route::get('/applyable', 'BadasoConfigurationsController@applyable');
            Route::get('/maintenance', 'BadasoConfigurationsController@isMaintenance');

            Route::get('/', 'BadasoConfigurationsController@browse')->middleware(BadasoCheckPermissions::class.':browse_configurations');
            Route::get('/read', 'BadasoConfigurationsController@read')->middleware(BadasoCheckPermissions::class.':read_configurations');
            Route::get('/fetch', 'BadasoConfigurationsController@fetch');
            Route::get('/fetch-multiple', 'BadasoConfigurationsController@fetchMultiple');
            Route::put('/edit', 'BadasoConfigurationsController@edit')->middleware(BadasoCheckPermissions::class.':edit_configurations');
            Route::put('/edit-multiple', 'BadasoConfigurationsController@editMultiple')->middleware(BadasoCheckPermissions::class.':edit_configurations');
            Route::post('/add', 'BadasoConfigurationsController@add')->middleware(BadasoCheckPermissions::class.':add_configurations');
            Route::delete('/delete', 'BadasoConfigurationsController@delete')->middleware(BadasoCheckPermissions::class.':delete_configurations');
        });

        Route::group(['prefix' => 'menus'], function () {
            Route::get('/', 'BadasoMenuController@browseMenu')->middleware(BadasoCheckPermissions::class.':browse_menus');
            Route::get('/read', 'BadasoMenuController@readMenu')->middleware(BadasoCheckPermissions::class.':read_menus');
            Route::put('/edit', 'BadasoMenuController@editMenu')->middleware(BadasoCheckPermissions::class.':edit_menus');
            Route::post('/add', 'BadasoMenuController@addMenu')->middleware(BadasoCheckPermissions::class.':add_menus');
            Route::delete('/delete', 'BadasoMenuController@deleteMenu')->middleware(BadasoCheckPermissions::class.':delete_menus');
            Route::put('/arrange-items', 'BadasoMenuController@editMenuItemsOrder')->middleware(BadasoCheckPermissions::class.':edit_menus');

            Route::get('/item', 'BadasoMenuController@browseMenuItem')->middleware(BadasoCheckPermissions::class.':browse_menu_items');
            Route::get('/item/read', 'BadasoMenuController@readMenuItem')->middleware(BadasoCheckPermissions::class.':read_menu_items');
            Route::put('/item/edit', 'BadasoMenuController@editMenuItem')->middleware(BadasoCheckPermissions::class.':edit_menu_items');
            Route::put('/item/edit-order', 'BadasoMenuController@editMenuItemOrder')->middleware(BadasoCheckPermissions::class.':edit_menu_items');
            Route::post('/item/add', 'BadasoMenuController@addMenuItem')->middleware(BadasoCheckPermissions::class.':add_menu_items');
            Route::delete('/item/delete', 'BadasoMenuController@deleteMenuItem')->middleware(BadasoCheckPermissions::class.':delete_menu_items');
            Route::get('/item/permissions', 'BadasoMenuController@getMenuItemPermissions')->middleware(BadasoCheckPermissions::class.':edit_menu_items');
            Route::put('/item/permissions', 'BadasoMenuController@setMenuItemPermissions')->middleware(BadasoCheckPermissions::class.':edit_menu_items');

            Route::get('/item-by-key', 'BadasoMenuController@browseMenuItemByKey');
            Route::get('/item-by-keys', 'BadasoMenuController@browseMenuItemByKeys');

            Route::put('/menu-options', 'BadasoMenuController@menuOptions');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'BadasoUserController@browse')->middleware(BadasoCheckPermissions::class.':browse_users');
            Route::get('/read', 'BadasoUserController@read')->middleware(BadasoCheckPermissions::class.':read_users');
            Route::put('/edit', 'BadasoUserController@edit')->middleware(BadasoCheckPermissions::class.':edit_users');
            Route::post('/add', 'BadasoUserController@add')->middleware(BadasoCheckPermissions::class.':add_users');
            Route::delete('/delete', 'BadasoUserController@delete')->middleware(BadasoCheckPermissions::class.':delete_users');
            Route::delete('/delete-multiple', 'BadasoUserController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_users');
        });

        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', 'BadasoPermissionController@browse')->middleware(BadasoCheckPermissions::class.':browse_permissions');
            Route::get('/read', 'BadasoPermissionController@read')->middleware(BadasoCheckPermissions::class.':read_permissions');
            Route::put('/edit', 'BadasoPermissionController@edit')->middleware(BadasoCheckPermissions::class.':edit_permissions');
            Route::post('/add', 'BadasoPermissionController@add')->middleware(BadasoCheckPermissions::class.':add_permissions');
            Route::delete('/delete', 'BadasoPermissionController@delete')->middleware(BadasoCheckPermissions::class.':delete_permissions');
            Route::delete('/delete-multiple', 'BadasoPermissionController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_permissions');
        });

        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', 'BadasoRoleController@browse')->middleware(BadasoCheckPermissions::class.':browse_roles');
            Route::get('/read', 'BadasoRoleController@read')->middleware(BadasoCheckPermissions::class.':read_roles');
            Route::put('/edit', 'BadasoRoleController@edit')->middleware(BadasoCheckPermissions::class.':edit_roles');
            Route::post('/add', 'BadasoRoleController@add')->middleware(BadasoCheckPermissions::class.':add_roles');
            Route::delete('/delete', 'BadasoRoleController@delete')->middleware(BadasoCheckPermissions::class.':delete_roles');
            Route::delete('/delete-multiple', 'BadasoRoleController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_roles');
        });

        Route::group(['prefix' => 'user-roles'], function () {
            Route::get('/', 'BadasoUserRoleController@browseByUser')->middleware(BadasoCheckPermissions::class.':browse_user_role');
            Route::get('/all', 'BadasoUserRoleController@browse')->middleware(BadasoCheckPermissions::class.':browse_user_role');
            Route::post('/add-edit', 'BadasoUserRoleController@addOrEdit')->middleware(BadasoCheckPermissions::class.':add_or_edit_user_role');
            Route::get('/all-role', 'BadasoUserRoleController@browseAllRole')->middleware(BadasoCheckPermissions::class.':browse_user_role');
        });

        Route::group(['prefix' => 'role-permissions'], function () {
            Route::get('/', 'BadasoRolePermissionController@browseByRole')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
            Route::get('/all', 'BadasoRolePermissionController@browse')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
            Route::post('/add-edit', 'BadasoRolePermissionController@addOrEdit')->middleware(BadasoCheckPermissions::class.':add_or_edit_role_permission');
            Route::get('/all-permission', 'BadasoRolePermissionController@browseAllPermission')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
        });

        Route::group(['prefix' => 'crud'], function () {
            Route::get('/', 'BadasoCRUDController@browse')->middleware(BadasoCheckPermissions::class.':browse_crud_data');
            Route::get('/read', 'BadasoCRUDController@read')->middleware(BadasoCheckPermissions::class.':read_crud_data');
            Route::put('/edit', 'BadasoCRUDController@edit')->middleware(BadasoCheckPermissions::class.':edit_crud_data');
            Route::post('/add', 'BadasoCRUDController@add')->middleware(BadasoCheckPermissions::class.':add_crud_data');
            Route::delete('/delete', 'BadasoCRUDController@delete')->middleware(BadasoCheckPermissions::class.':delete_crud_data');
            Route::get('/read-by-slug', 'BadasoCRUDController@readBySlug')->middleware(BadasoCheckPermissions::class.':read_crud_data');
            Route::get('/maintenance', 'BadasoCRUDController@setMaintenanceState')->middleware(BadasoCheckPermissions::class.':maintenance_crud_data');
        });

        Route::group(['prefix' => 'table'], function () {
            Route::get('/data-type', 'BadasoTableController@getDataType')->middleware(BadasoAuthenticate::class);
            Route::get('/', 'BadasoTableController@browse')->middleware(BadasoAuthenticate::class);
            Route::get('/read', 'BadasoTableController@read')->middleware(BadasoAuthenticate::class);
            Route::get('/data', 'BadasoTableController@getDataByTable')->middleware(BadasoAuthenticate::class);
            Route::get('/generate-crud', 'BadasoTableController@generateCRUD')->middleware(BadasoAuthenticate::class);
            Route::get('/relation-data-by-slug', 'BadasoTableController@getRelationDataBySlug')->middleware(BadasoAuthenticate::class);
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
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',browse');

                    Route::get($data_type->slug.'/read', $crud_data_controller.'@read')
                        ->name($data_type->slug.'.read')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',read');

                    Route::put($data_type->slug.'/edit', $crud_data_controller.'@edit')
                        ->name($data_type->slug.'.edit')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',edit');

                    Route::post($data_type->slug.'/add', $crud_data_controller.'@add')
                        ->name($data_type->slug.'.add')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',add');

                    Route::delete($data_type->slug.'/delete', $crud_data_controller.'@delete')
                        ->name($data_type->slug.'.delete')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',delete');

                    Route::delete($data_type->slug.'/restore', $crud_data_controller.'@restore')
                        ->name($data_type->slug.'.restore')->middleware(BadasoAuthenticate::class);

                    Route::delete($data_type->slug.'/delete-multiple', $crud_data_controller.'@deleteMultiple')
                        ->name($data_type->slug.'.delete-multiple')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',delete');

                    Route::delete($data_type->slug.'/restore-multiple', $crud_data_controller.'@restoreMultiple')
                        ->name($data_type->slug.'.restore-multiple')->middleware(BadasoAuthenticate::class);

                    Route::put($data_type->slug.'/sort', $crud_data_controller.'@sort')
                        ->name($data_type->slug.'.sort')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',edit');

                    Route::get($data_type->slug.'/all', $crud_data_controller.'@all')
                        ->name($data_type->slug.'.all')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',edit');

                    Route::post($data_type->slug.'/maintenance', $crud_data_controller.'@setMaintenanceState')
                        ->name($data_type->slug.'.maintenance')
                        ->middleware(BadasoCheckPermissionsForCRUD::class.':'.$data_type->slug.',maintenance');
                }
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
            } catch (\Exception $e) {
                // do nothing, might just be because table not yet migrated.
            }
        });

        Route::group(['prefix' => 'database'], function () {
            Route::get('/', 'BadasoDatabaseController@browse')->middleware(BadasoCheckPermissions::class.':browse_database');
            Route::get('/read', 'BadasoDatabaseController@read')->middleware(BadasoCheckPermissions::class.':read_database');
            Route::put('/edit', 'BadasoDatabaseController@edit')->middleware(BadasoCheckPermissions::class.':edit_database');
            Route::post('/add', 'BadasoDatabaseController@add')->middleware(BadasoCheckPermissions::class.':add_database');
            Route::delete('/delete', 'BadasoDatabaseController@delete')->middleware(BadasoCheckPermissions::class.':delete_database');
            Route::post('/rollback', 'BadasoDatabaseController@rollback')->middleware(BadasoCheckPermissions::class.':rollback_database');
            Route::get('/migration/browse', 'BadasoDatabaseController@browseMigration')->middleware(BadasoCheckPermissions::class.':browse_migration');
            Route::get('/migration/status', 'BadasoDatabaseController@checkMigrateStatus');
            Route::get('/migration/migrate', 'BadasoDatabaseController@migrate')->middleware(BadasoCheckPermissions::class.':migrate_database');
            Route::post('/migration/delete', 'BadasoDatabaseController@deleteMigration')->middleware(BadasoCheckPermissions::class.':delete_migration');
            Route::get('/type', 'BadasoDatabaseController@getDbmsFieldType')->middleware([BadasoCheckPermissions::class.':add_database', BadasoCheckPermissions::class.':edit_database']);
        });

        Route::group(['prefix' => 'firebase', 'middleware' => 'auth'], function () {
            Route::group(['prefix' => 'cloud_messages'], function () {
                Route::put('/save-token-messages', 'BadasoFCMController@saveTokenMessage');
            });
            Route::group(['prefix' => 'messages', 'middleware' => 'auth'], function () {
                Route::get('/', 'BadasoNotificationsController@getMessages');
                Route::put('/{id}', 'BadasoNotificationsController@readMessage');
                Route::get('/count-unread', 'BadasoNotificationsController@getCountUnreadMessage');
            });
        });
    });
});
