<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\DataType;

class BadasoApiCrudManagementTest extends TestCase
{
    private $KEY_LIST_CREATE_TABLES = 'LIST_CREATE_TABLES';
    private $KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG = 'DATA_TABLE_CRUD_MANAGEMENT_LOG';
    private $KEY_DATA_RESPONSE_ADD_CRUD_MANAGEMENT = 'DATA_RESPONSE_ADD_CRUD_MANAGEMENT';
    private $TABLE_TEST_PREFIX = 'test_table_';

    private function getFields(): array
    {
        return
            $field_name = [
                [
                    'badaso_type' => 'text',
                    'schema_type' => 'text',
                ],
                [
                    'badaso_type' => 'email',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'password',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'textarea',
                    'schema_type' => 'text',
                ],
                [
                    'badaso_type' => 'checkbox',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'search',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'number',
                    'schema_type' => 'integer',
                ],
                [
                    'badaso_type' => 'url',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'time',
                    'schema_type' => 'time',
                ],
                [
                    'badaso_type' => 'date',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'datetime',
                    'schema_type' => 'datetime',
                ],
                [
                    'badaso_type' => 'select',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'radio',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'switch',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'slider',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'editor',
                    'schema_type' => 'text',
                ],
                [
                    'badaso_type' => 'tags',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'code',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'hidden',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'relation',
                    'schema_type' => 'bigInteger',
                ],
                [
                    'badaso_type' => 'color_picker',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'upload_image',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'select_multiple',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'upload_file',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'upload_image_multiple',
                    'schema_type' => 'string',
                ],
                [
                    'badaso_type' => 'upload_file_multiple',
                    'schema_type' => 'string',
                ],
            ];
    }

    private function createTestTables(int $max_count_table_generate)
    {
        $table_names = [];
        for ($index = 1; $index <= $max_count_table_generate; $index++) {
            $table_name = "{$this->TABLE_TEST_PREFIX}{$index}";
            if (! Schema::hasTable($table_name)) {
                Schema::create($table_name, function (Blueprint $table) use ($index, $table_names) {
                    $table->id();

                    foreach ($this->getFields() as $key => ['badaso_type' => $badaso_type, 'schema_type' => $schema_type]) {
                        if ($badaso_type == 'relation') {
                            if ($index >= 2) {
                                $table_name_relation = $table_names[0];
                                $table->{$schema_type}($badaso_type)->nullable()->unsigned();

                                $table->foreign($badaso_type)->references('id')->on($table_name_relation)->onDelete('cascade');
                            }
                        } else {
                            $table->{$schema_type}($badaso_type)->nullable();
                        }
                    }
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
            $table_names[] = $table_name;
        }

        // save all tables name to cache
        CallHelperTest::setCache($this->KEY_LIST_CREATE_TABLES, $table_names);
    }

    private function deleteAllTestTables()
    {
        $table_names = collect(CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES))->reverse();
        foreach ($table_names as $key => $table_names) {
            Schema::dropIfExists($table_names);
        }

        // clear cache
        CallHelperTest::clearCache();
    }

    public function testStartInit()
    {
        // init user login
        CallHelperTest::handleUserAdminAuthorize($this);

        // init create all tables testing
        $this->createTestTables(10);
    }

    public function testBrowseCrudManagement()
    {
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud'));
        $response->assertSuccessful();

        $expect_table = config('badaso-watch-tables');
        $with_crud_data_tables = $response->json('data.tablesWithCrudData');

        foreach ($with_crud_data_tables as $key => $with_crud_data_table) {
            $table_name = $with_crud_data_table['tableName'];
            $this->assertIsArray($expect_table, $table_name);
        }
    }

    public function testAddCrudManagement()
    {
        $table_names = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);
        $const_fields = $this->getFields();

        // delete  all data type
        $data_types = DataType::whereIn('slug', $table_names)->get();
        foreach ($data_types as $key => $data_type) {
            $data_type->delete();
        }

        $data_table_crud_management_log = [];
        $data_response_add_crud_management = [];
        foreach ($table_names as $index_table_name => $table_name) {
            $rows = [
                [
                    'field' => 'id',
                    'type' => 'integer',
                    'displayName' => 'Id',
                    'required' => rand(0, 1),
                    'browse' => rand(0, 1),
                    'read' => rand(0, 1),
                    'edit' => rand(0, 1),
                    'add' => rand(0, 1),
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ],
                [
                    'field' => 'created_at',
                    'type' => 'datetime',
                    'displayName' => 'Created At',
                    'required' => rand(0, 1),
                    'browse' => rand(0, 1),
                    'read' => rand(0, 1),
                    'edit' => rand(0, 1),
                    'add' => rand(0, 1),
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ],
                [
                    'field' => 'updated_at',
                    'type' => 'datetime',
                    'displayName' => 'Update At',
                    'required' => rand(0, 1),
                    'browse' => rand(0, 1),
                    'read' => rand(0, 1),
                    'edit' => rand(0, 1),
                    'add' => rand(0, 1),
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ],
                [
                    'field' => 'deleted_at',
                    'type' => 'datetime',
                    'displayName' => 'Deleted At',
                    'required' => rand(0, 1),
                    'browse' => rand(0, 1),
                    'read' => rand(0, 1),
                    'edit' => rand(0, 1),
                    'add' => rand(0, 1),
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ],
            ];
            foreach ($const_fields as $key => ['badaso_type' => $badaso_type, 'schema_type' => $schema_type]) {
                if ($index_table_name == 0 && $badaso_type == 'relation') {
                    continue;
                }

                $field_name = ucwords(str_replace(['_'], ' ', $badaso_type));
                $row = [
                    'field' => $badaso_type,
                    'type' => $badaso_type,
                    'displayName' => $field_name,
                    'required' => rand(0, 1),
                    'browse' => rand(0, 1),
                    'read' => rand(0, 1),
                    'edit' => rand(0, 1),
                    'add' => rand(0, 1),
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ];

                if ($badaso_type == 'relation') {
                    $destination_field = $const_fields[rand(0, count($const_fields) - 1)];
                    $row['relationType'] = ['belongs_to', 'has_one', 'has_many'][rand(0, 2)];
                    $row['relationType'] = true;
                    $row['destinationTable'] = $table_names[0];
                    $row['destinationTableColumn'] = $destination_field;
                    $row['destinationTableDisplayColumn'] = $destination_field;
                }

                $rows[] = $row;
            }
            $data_table_crud_management_log[$index_table_name]['rows'] = $rows;

            $model = '';
            $model_data = [];
            if (rand(0, 1)) {
                // create new model
                $model_name = str_replace([' ', '_'], '', ucwords($table_name));
                $model_file_name = "{$model_name}.php";
                $model_body = <<<PHP
                <?php
                namespace App\Models;
                use Illuminate\Database\Eloquent\Factories\HasFactory;
                use Illuminate\Database\Eloquent\Model;
                class {$model_name} extends Model {
                    use HasFactory;
                    protected \$table = "{$table_name}" ;
                }
                PHP;
                $model_path = app_path("Models/$model_file_name");
                if (! file_exists($model_path)) {
                    file_put_contents($model_path, $model_body);
                }

                // equals model namespace
                $model = "App\Models\\$model_name";

                $model_data = [
                    'model_name' => $model_name,
                    'model_file_name' => $model_file_name,
                    'model_body' => $model_body,
                    'model_path' => $model_path,
                    'model' => $model,
                ];
            }
            $data_table_crud_management_log[$index_table_name]['model'] = $model_data;

            $controller = '';
            $controller_data = [];
            if (rand(0, 1)) {
                // create new controller
                $controller_name = str_replace([' ', '_'], '', ucwords($table_name)).'Controller';
                $controller_file_name = "{$controller_name}.php";
                $controller_body = <<<PHP
                <?php
                namespace App\Http\Controllers;
                use Illuminate\Http\Request;
                class {$controller_name} extends \Uasoft\Badaso\Controllers\BadasoBaseController {}
                PHP;
                $controller_path = app_path("/Http/Controllers/$controller_file_name");
                if (! file_exists($controller_path)) {
                    file_put_contents($controller_path, $controller_body);
                }

                // equals controller namespace
                $controller = "App\Http\Controllers\\$controller_name";

                $controller_data = [
                    'controller_name' => $controller_file_name,
                    'controller_file_name' => $controller_file_name,
                    'controller_body' => $controller_body,
                    'controller_path' => $controller_path,
                    'controller' => $controller,
                ];
            }
            $data_table_crud_management_log[$index_table_name]['controller'] = $controller_data;

            $table_label = ucwords(str_replace(['_'], ' ', $table_name));
            $request_body = [
                'name' =>  $table_name,
                'slug' =>  $table_name,
                'displayNameSingular' =>  $table_label,
                'displayNamePlural' =>  $table_label,
                'icon' =>  'add',
                'modelName' =>  $model,
                'policyName' =>  '',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'generatePermissions' =>  rand(0, 1),
                'createSoftDelete' =>  rand(0, 1),
                'serverSide' =>  rand(0, 1),
                'details' =>  json_encode((object) []),
                'controller' =>  $controller,
                'orderColumn' =>  '',
                'orderDisplayColumn' =>  '',
                'orderDirection' =>  '',
                'notification' =>   array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 3)),
                'isMaintenance' => rand(0, 1),
                'rows' => $rows,
            ];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/crud/add'), $request_body);
            $response->assertSuccessful();

            // save logs
            $data_table_crud_management_log[$index_table_name]['request_body'] = $request_body;
            $data_response_add_crud_management[] = $response->json('data');
        }

        CallHelperTest::setCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG, $data_table_crud_management_log);
        CallHelperTest::setCache($this->KEY_DATA_RESPONSE_ADD_CRUD_MANAGEMENT, $data_response_add_crud_management);
    }

    public function testEditCrudManagement()
    {
        $data_table_crud_management_log = CallHelperTest::getCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG);
        $data_response_add_crud_managements = CallHelperTest::getCache($this->KEY_DATA_RESPONSE_ADD_CRUD_MANAGEMENT);

        foreach ($data_response_add_crud_managements as $index => $data_response_add_crud_management) {
            $table_name = $data_response_add_crud_management['name'];
            $rows = $data_response_add_crud_management['dataRows'];

            $table_label = ucwords(str_replace(['_'], ' ', $table_name));

            $model = '';
            $model_data = [];
            if (rand(0, 1)) {
                // create new model
                $model_name = str_replace([' ', '_'], '', ucwords($table_name));
                $model_file_name = "{$model_name}.php";
                $model_body = <<<PHP
                <?php
                namespace App\Models;
                use Illuminate\Database\Eloquent\Factories\HasFactory;
                use Illuminate\Database\Eloquent\Model;
                class {$model_name} extends Model {
                    use HasFactory;
                    protected \$table = "{$table_name}" ;
                }
                PHP;
                $model_path = app_path("Models/$model_file_name");
                if (! file_exists($model_path)) {
                    file_put_contents($model_path, $model_body);
                }

                // equals model namespace
                $model = "App\Models\\$model_name";

                $model_data = [
                    'model_name' => $model_name,
                    'model_file_name' => $model_file_name,
                    'model_body' => $model_body,
                    'model_path' => $model_path,
                    'model' => $model,
                ];
            }
            $data_table_crud_management_log[$index]['model'] = $model_data;

            $controller = '';
            $controller_data = [];
            if (rand(0, 1)) {
                // create new controller
                $controller_name = str_replace([' ', '_'], '', ucwords($table_name)).'Controller';
                $controller_file_name = "{$controller_name}.php";
                $controller_body = <<<PHP
                <?php
                namespace App\Http\Controllers;
                use Illuminate\Http\Request;
                class {$controller_name} extends \Uasoft\Badaso\Controllers\BadasoBaseController {}
                PHP;
                $controller_path = app_path("/Http/Controllers/$controller_file_name");
                if (! file_exists($controller_path)) {
                    file_put_contents($controller_path, $controller_body);
                }

                // equals controller namespace
                $controller = "App\Http\Controllers\\$controller_name";

                $controller_data = [
                    'controller_name' => $controller_file_name,
                    'controller_file_name' => $controller_file_name,
                    'controller_body' => $controller_body,
                    'controller_path' => $controller_path,
                    'controller' => $controller,
                ];
            }
            $data_table_crud_management_log[$index]['controller'] = $controller_data;

            $request_body = [
                'name' =>  $table_name,
                'slug' =>  $table_name,
                'displayNameSingular' =>  $table_label.'(update)',
                'displayNamePlural' =>  $table_label.'(update)',
                'icon' =>  'add',
                'modelName' =>  $model,
                'policyName' =>  '',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. (update)',
                'generatePermissions' =>  rand(0, 1),
                'createSoftDelete' =>  rand(0, 1),
                'serverSide' =>  rand(0, 1),
                'details' =>  json_encode((object) []),
                'controller' =>  $controller,
                'orderColumn' =>  '',
                'orderDisplayColumn' =>  '',
                'orderDirection' =>  '',
                'notification' =>   array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 0)),
                'rows' => $rows,
            ];

            foreach ($request_body as $key => $value) {
                $data_response_add_crud_management[$key] = $value;
            }

            $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/crud/edit'), $data_response_add_crud_management);
            $response->assertSuccessful();
        }

        CallHelperTest::setCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG, $data_table_crud_management_log);
    }

    public function testReadCrudManagement()
    {
        $data_table_crud_management_logs = CallHelperTest::getCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG);

        foreach ($data_table_crud_management_logs as $key => $data_table_crud_management_log) {
            $request_body = $data_table_crud_management_log['request_body'];
            $name = $request_body['name'];

            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud/read'), [
                'table' => $name,
            ]);
            $response->assertSuccessful();
        }
    }

    public function testReadBySlugCrudManagement()
    {
        $data_table_crud_management_logs = CallHelperTest::getCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG);

        foreach ($data_table_crud_management_logs as $key => $data_table_crud_management_log) {
            $request_body = $data_table_crud_management_log['request_body'];
            $slug = $request_body['slug'];

            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud/read-by-slug'), [
                'slug' => $slug,
            ]);
            $response->assertSuccessful();
        }
    }

    public function testDeleteCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);

        $data_types = DataType::whereIn('name', $tables)->get();
        foreach ($data_types as $key => $data_type) {
            $table_name = $data_type['name'];
            $name = ucwords(str_replace('_', '', $table_name));

            $id = $data_type->id;
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/crud/delete'), [
                'id' => $id,
            ]);
            $response->assertSuccessful();

            // delete controller
            $controller_name = "{$name}Controller.php";
            $controller_path = app_path('Http/Controllers/'.$controller_name);
            if (file_exists($controller_path)) {
                unlink($controller_path);
            }

            // delete models
            $model_name = "{$name}.php";
            $model_path = app_path('Models/'.$model_name);
            if (file_exists($model_path)) {
                unlink($model_path);
            }
        }
        $data_types = DataType::whereIn('name', $tables)->get();
        $this->assertEmpty($data_types);
    }

    public function testFinish()
    {
        // clear table and cache table
        $this->deleteAllTestTables();

        $this->assertTrue(true);
    }
}
