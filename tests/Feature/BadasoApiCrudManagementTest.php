<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Migration;

class BadasoApiCrudManagementTest extends TestCase
{
    private $KEY_LIST_CREATE_TABLES = 'LIST_CREATE_TABLES';
    private $KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG = 'DATA_TABLE_CRUD_MANAGEMENT_LOG';
    private $KEY_DATA_RESPONSE_ADD_CRUD_MANAGEMENT = 'DATA_RESPONSE_ADD_CRUD_MANAGEMENT';
    private $KEY_DATA_RESPONSE_READ_TABLE_ENTITY = 'KEY_DATA_RESPONSE_READ_TABLE_ENTITY';
    private $KEY_DATA_ADD_ENTITY = 'KEY_DATA_ADD_ENTITY';
    private $TABLE_TEST_PREFIX = 'test_table_';
    private $MAXIMAL_CREATE_ENTITY = 3;

    private function getFields(): array
    {
        return
            $field_name = [
                [
                    'badaso_type' => 'text',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'text',
                    'example_update' => 'text.update',
                ],
                [
                    'badaso_type' => 'email',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'email@example.com',
                    'example_update' => 'update.email@example.com',
                ],
                [
                    'badaso_type' => 'password',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'password',
                    'example_update' => 'password.update',
                ],
                [
                    'badaso_type' => 'textarea',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'textarea',
                    'example_update' => 'textarea.update',
                ],
                [
                    'badaso_type' => 'checkbox',
                    'schema_type' => 'string',
                    'details' => json_encode([
                        'items' => [
                            ['label' => 'example_1', 'value' => 'example_1'],
                            ['label' => 'example_2', 'value' => 'example_2'],
                        ],
                    ]),
                    'example' => ['example_1'],
                    'example_update' => ['example_2'],
                ],
                [
                    'badaso_type' => 'search',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'search',
                    'example_update' => 'search.update',
                ],
                [
                    'badaso_type' => 'number',
                    'schema_type' => 'integer',
                    'details' => json_encode((object) []),
                    'example' => 1,
                    'example_update' => 2,
                ],
                [
                    'badaso_type' => 'url',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'https://badaso-docs.uatech.co.id',
                    'example_update' => 'https://badaso.uatech.co.id',
                ],
                [
                    'badaso_type' => 'time',
                    'schema_type' => 'time',
                    'details' => json_encode((object) []),
                    'example' => '2022-01-27T04:37:18.327Z',
                    'example_update' => '2023-01-27T04:37:18.327Z',
                ],
                [
                    'badaso_type' => 'date',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => '2022-01-27T04:37:18.327Z',
                    'example_update' => '2023-01-27T04:37:18.327Z',
                ],
                [
                    'badaso_type' => 'datetime',
                    'schema_type' => 'datetime',
                    'details' => json_encode((object) []),
                    'example' => '2022-01-27T04:37:18.327Z',
                    'example_update' => '2023-01-27T04:37:18.327Z',
                ],
                [
                    'badaso_type' => 'select',
                    'schema_type' => 'string',
                    'details' => json_encode([
                        'items' => [
                            ['label' => 'example_1', 'value' => 'example_1'],
                            ['label' => 'example_2', 'value' => 'example_2'],
                        ],
                    ]),
                    'example' => 'example_1',
                    'example_update' => 'example_2',
                ],
                [
                    'badaso_type' => 'radio',
                    'schema_type' => 'string',
                    'details' => json_encode([
                        'items' => [
                            ['label' => 'example_1', 'value' => 'example_1'],
                            ['label' => 'example_2', 'value' => 'example_2'],
                        ],
                    ]),
                    'example' => 'example_1',
                    'example_update' => 'example_2',
                ],
                [
                    'badaso_type' => 'switch',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => rand(0, 1),
                    'example_update' => rand(0, 1),
                ],
                [
                    'badaso_type' => 'slider',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => rand(1, 50),
                    'example_update' => rand(50, 100),
                ],
                [
                    'badaso_type' => 'editor',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'editor',
                    'example_update' => 'editor.update',
                ],
                [
                    'badaso_type' => 'tags',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => join(',', ['badaso', 'test', 'crud', 'management']),
                    'example_update' => join(',', ['badaso', 'test', 'crud', 'management', 'update']),
                ],
                [
                    'badaso_type' => 'code',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'code',
                    'example_update' => 'code.update',
                ],
                [
                    'badaso_type' => 'hidden',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'hidden',
                    'example_update' => 'hidden.update',
                ],
                [
                    'badaso_type' => 'relation',
                    'schema_type' => 'bigInteger',
                    'details' => json_encode((object) []),
                    'example' => null,
                ],
                [
                    'badaso_type' => 'color_picker',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => '#000000',
                    'example_update' => '#FFFFFF',
                ],
                [
                    'badaso_type' => 'upload_image',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png',
                    'example_update' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                ],
                [
                    'badaso_type' => 'select_multiple',
                    'schema_type' => 'string',
                    'details' => json_encode([
                        'items' => [
                            ['label' => 'example_1', 'value' => 'example_1'],
                            ['label' => 'example_2', 'value' => 'example_2'],
                            ['label' => 'example_3', 'value' => 'example_3'],
                        ],
                    ]),
                    'example' => [
                        'example_1',
                        'example_2',
                    ],
                    'example_update' => [
                        'example_2',
                        'example_3',
                    ],
                ],
                [
                    'badaso_type' => 'upload_file',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png',
                    'example_update' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                ],
                [
                    'badaso_type' => 'upload_image_multiple',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => "['https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png','https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png']",
                    'example_update' => [
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                    ],
                ],
                [
                    'badaso_type' => 'upload_file_multiple',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => [
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png',
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png',
                    ],
                    'example_update' => [
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                        'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                    ],
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
        $const_fillable = collect($const_fields)->map(function ($fillable) {
            $field = $fillable['badaso_type'];

            return "\"$field\"";
        })->toArray();
        $const_fillable[] = '"deleted_at"';

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
                    'edit' => 0,
                    'add' => 0,
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
                    'edit' => 0,
                    'add' => 0,
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
                    'edit' => 0,
                    'add' => 0,
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
                    'edit' => 0,
                    'add' => 0,
                    'delete' => rand(0, 1),
                    'details' => json_encode((object) []),
                    'order' => 1,
                    'setRelation' => false,
                ],
            ];
            foreach ($const_fields as $key => ['badaso_type' => $badaso_type, 'schema_type' => $schema_type, 'details' => $details]) {
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
                    'details' => $details,
                    'order' => 1,
                    'setRelation' => false,
                ];

                if ($badaso_type == 'relation') {
                    $destination_field['badaso_type'] = 'id';
                    $destination_more_field['badaso_type'] = ['select_multiple'];

                    $row['relationType'] = ['belongs_to', 'has_one', 'has_many'][rand(0, 2)];
                    $row['relationType'] = true;
                    $row['destinationTable'] = $table_names[0];
                    $row['destinationTableColumn'] = $destination_field['badaso_type'];
                    $row['destinationTableDisplayColumn'] = $destination_field['badaso_type'];
                    $row['destinationTableDisplayMoreColumn'] = $destination_more_field['badaso_type'];
                    $row['required'] = false;
                }

                $rows[] = $row;
            }
            $data_table_crud_management_log[$index_table_name]['rows'] = $rows;

            $model = '';
            $model_data = [];
            if (rand(0, 1)) {
                // create new model
                $fillable = join(',', $const_fillable);
                $model_name = str_replace([' ', '_'], '', ucwords($table_name));
                $model_file_name = "{$model_name}.php";
                $model_body = <<<PHP
                <?php
                namespace App\Models;
                use Illuminate\Database\Eloquent\Model;
                class {$model_name} extends Model {
                    protected \$table = "{$table_name}" ;
                    protected \$fillable = [$fillable] ;
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

    public function testReadCrudManagement()
    {
        $data_table_crud_management_logs = CallHelperTest::getCache($this->KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG);
        $response_read_table_entities = [];
        foreach ($data_table_crud_management_logs as $key => $data_table_crud_management_log) {
            $request_body = $data_table_crud_management_log['request_body'];
            $table = $request_body['name'];

            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud/read'), [
                'table' => $table,
            ]);
            $response->assertSuccessful();

            $response_read_table_entities[$table] = $response->json('data.crud.dataRows');
        }

        CallHelperTest::setCache($this->KEY_DATA_RESPONSE_READ_TABLE_ENTITY, $response_read_table_entities);
    }

    public function testAddEditEntityCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);
        $first_table = $tables[0];

        $response_read_table_entities = CallHelperTest::getCache($this->KEY_DATA_RESPONSE_READ_TABLE_ENTITY);
        $fields = [];

        foreach ($this->getFields() as $key => $value) {
            $fields[$value['badaso_type']] = $value;
        }

        $data_add_entities = [];
        foreach ($response_read_table_entities as $table => $entities) {
            $entities = collect($entities)->filter(function ($entity) {
                return $entity['add'];
            })->values();

            $data_add_entities[$table] = [];
            for ($index = 1; $index <= $this->MAXIMAL_CREATE_ENTITY; $index++) {
                // create
                $data = [];
                foreach ($entities as $key => $entity) {
                    $field = $entity['field'];
                    if (array_key_exists($field, $fields)) {
                        if ($field == 'relation') {
                            $relation_value = DB::table($first_table)->insertGetId([]);
                            $data[$field] = $relation_value;
                        } else {
                            $data[$field] = $fields[$field]['example'];
                        }
                    }
                }
                $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/add"), [
                    'data' => $data,
                ]);
                $response->assertSuccessful();

                // check value row table after create
                $id = $response->json('data.id');
                $table_row = DB::table($table)->where('id', $id)->first();
                foreach ($data as $key => $value) {
                    switch ($key) {
                        case 'time':
                            $z_removed = explode('.', $value)[0];
                            $time = explode('T', $z_removed)[1];
                            $value = $time;
                            break;
                        case 'date':
                            $z_removed = explode('.', $value)[0];
                            $date = explode('T', $z_removed)[0];
                            $value = $date;
                            break;
                        case 'datetime':
                            $z_removed = explode('.', $value)[0];
                            $date_time = str_replace('T', ' ', $z_removed);
                            $value = $date_time;
                            break;
                    }

                    if (is_array($value)) {
                        $this->assertSame($table_row->{$key}, join(',', $value));
                    } else {
                        if ('password' == $key) {
                            $this->assertTrue(Hash::check($value, $table_row->{$key}));
                        } else {
                            $this->assertTrue($table_row->{$key} == $value);
                        }
                    }
                }

                // update
                $data = ['id' => $id];
                foreach ($entities as $key => $entity) {
                    $field = $entity['field'];
                    if (array_key_exists($field, $fields)) {
                        if ($field == 'relation') {
                            $relation_value = DB::table($first_table)->insertGetId([]);
                            $data[$field] = $relation_value;
                        } else {
                            $data[$field] = $fields[$field]['example_update'];
                        }
                    }
                }
                $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/edit"), [
                    'data' => $data,
                ]);
                $response->assertSuccessful();

                $data_add_entities[$table][] = $response->json('data');
            }
        }

        CallHelperTest::setCache($this->KEY_DATA_ADD_ENTITY, $data_add_entities);
    }

    public function testReadDataEntityCrudManagement()
    {
        $data_add_entities = CallHelperTest::getCache($this->KEY_DATA_ADD_ENTITY);
        foreach ($data_add_entities as $table => $data_add_entity) {
            foreach ($data_add_entity as $index => $entity) {
                $id = $entity['id'];

                $table_entity = DB::table($table)->where('id', $id)->first();
                if (isset($table_entity)) {
                    $response = CallHelperTest::withAuthorizeBearer($this)
                        ->json('GET', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/read"), [
                            'id' => $id,
                        ]);

                    $response->assertSuccessful();
                }
            }
        }
    }

    public function testBrowseDataEntityCrudManagement()
    {
        $data_add_entities = CallHelperTest::getCache($this->KEY_DATA_ADD_ENTITY);
        foreach ($data_add_entities as $table => $data_add_entity) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}"), [
                'page' => 1,
                'limit' => 10,
            ]);
            $response->assertSuccessful();
        }
    }

    public function testGetAllDataEntityCrudManagement()
    {
        $data_add_entities = CallHelperTest::getCache($this->KEY_DATA_ADD_ENTITY);
        foreach ($data_add_entities as $table => $data_add_entity) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/all"));
            $response->assertSuccessful();
        }
    }

    public function testSingleMultipleDeleteEntityCrudManagement()
    {
        $data_add_entities = CallHelperTest::getCache($this->KEY_DATA_ADD_ENTITY);

        foreach ($data_add_entities as $table => $data_add_entity) {
            $id = $data_add_entity[0]['id'];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/delete"), [
                'slug' => $table,
                'data' => [
                    [
                        'field' => 'id',
                        'value' => $id,
                    ],
                ],
            ]);
            $response->assertSuccessful();

            // cek data from database
            $table_row = DB::table($table)->where('id', $id)->first();
            if (isset($table_row)) {
                $this->assertNotEmpty($table_row->deleted_at);
            } else {
                $this->assertEmpty($table_row);
            }

            // get ids for multiple delete
            $ids = [];
            foreach ($data_add_entity as $index => $entity) {
                if ($index > 0) {
                    $ids[] = $entity['id'];
                }
            }
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix("/entities/{$table}/delete-multiple"), [
                'slug' => $table,
                'data' => [
                    [
                        'field' => 'ids',
                        'value' => join(',', $ids),
                    ],
                ],
            ]);
            $response->assertSuccessful();

            // cek data from database
            $table_row_count = DB::table($table)->whereIn('id', $ids);
            if ($table_row_count->count() != 0) {
                $table_row_count = $table_row_count->whereNotNull('deleted_at');
                $this->assertTrue($table_row_count->count() == count($ids));

                $table_row_count->delete();
            } else {
                $this->assertTrue($table_row_count->count() == 0);
            }
        }
    }

    public function testReadTableEntityResultCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);

        foreach ($tables as $key => $slug) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/table/read'), [
                'table' => $slug,
            ]);
            $response->assertSuccessful();
        }
    }

    public function testRelationDataBySlugEntityResultCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);
        foreach ($tables as $key => $slug) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/table/relation-data-by-slug'), [
                'slug' => $slug,
            ]);

            $response->assertSuccessful();
        }
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

    public function testReadTableResultAfterEditCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);
        foreach ($tables as $key => $slug) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/table/read'), [
                'table' => $slug,
            ]);

            $response->assertSuccessful();
        }
    }

    public function testRelationDataBySlugResultAfterEditCrudManagement()
    {
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES);
        foreach ($tables as $key => $slug) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/table/relation-data-by-slug'), [
                'slug' => $slug,
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

    public function testRollbackMigration()
    {
        //Define table 1
        $table_1 = [
            'table' => 'tests_table_12',
            'rows' => [
                [
                    'id' => 'id',
                    'fieldName' => 'id',
                    'fieldType' => 'bigint',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => true,
                    'fieldIncrement' => true,
                    'fieldIndex' => 'primary',
                    'fieldDefault' => null,
                    'undeletable' => true,
                ],
                [
                    'fieldName' => 'created_at',
                    'fieldType' => 'timestamp',
                    'fieldLength' => null,
                    'fieldNull' => true,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => null,
                    'undeletable' => true,
                    'indexes' => true,
                ],
                [
                    'fieldName' => 'updated_at',
                    'fieldType' => 'timestamp',
                    'fieldLength' => null,
                    'fieldNull' => true,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => null,
                    'undeletable' => true,
                ],
            ],
            'relations' => [],
        ];

        //define table 2 for relation with table 1
        $table_2 = [
            'table' => 'tests_table_13',
            'rows' => [
                [
                    'id' => 'id',
                    'fieldName' => 'id',
                    'fieldType' => 'bigint',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => true,
                    'fieldIncrement' => true,
                    'fieldIndex' => 'primary',
                    'fieldDefault' => null,
                    'undeletable' => true,
                ],
                [
                    'id' => 'f8fe0661-d861-41e7-83df-65c09b5d2e7a',
                    'fieldName' => 'table_12_id',
                    'fieldType' => 'bigint',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => true,
                    'fieldIncrement' => false,
                    'fieldIndex' => 'foreign',
                    'fieldDefault' => '',
                ],
                [
                    'id' => 'f769fad7-d192-44d2-9845-86ce55596551',
                    'fieldName' => 'f2',
                    'fieldType' => 'varchar',
                    'fieldLength' => '255',
                    'fieldNull' => false,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => '',
                ],
                [
                    'fieldName' => 'created_at',
                    'fieldType' => 'timestamp',
                    'fieldLength' => null,
                    'fieldNull' => true,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => null,
                    'undeletable' => true,
                    'indexes' => true,
                ],
                [
                    'fieldName' => 'updated_at',
                    'fieldType' => 'timestamp',
                    'fieldLength' => null,
                    'fieldNull' => true,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => null,
                    'undeletable' => true,
                ],
            ],
            'relations' => [
                'f8fe0661-d861-41e7-83df-65c09b5d2e7a' => [
                    'sourceField' => 'table_12_id',
                    'targetTable' => 'tests_table_12',
                    'targetField' => 'id',
                    'onDelete' => 'cascade',
                    'onUpdate' => 'restrict',
                ],
            ],
        ];
        //array have value two table above
        $list_table = [$table_1, $table_2];

        //add table
        foreach ($list_table as $key => $value) {
            $response = CallHelperTest::withAuthorizeBearer($this)
                ->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/add'), $value);
            $response->assertSuccessful();
        }

        //browse table to get file name migration
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('GET', CallHelperTest::getUrlApiV1Prefix('/database/migration/browse'));
        $response = $response->json('data');
        $migration_name = [];
        for ($i = count($response) - 2; $i < count($response); $i++) {
            $migration_name[] = $response[$i]['migration'];
        }

        //rollback migration
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/rollback'), [
                'step' => 2,
            ]);
        $response->assertSuccessful();

        //delete file migration
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/migration/delete'), [
                'file_name' => $migration_name,
            ]);
        $response->assertSuccessful();
    }

    public function testFinish()
    {
        // clear table and cache table
        $this->deleteAllTestTables();

        $this->assertTrue(true);
    }
}
