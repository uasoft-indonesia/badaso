<?php

namespace Uasoft\Badaso\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Migration;
use Uasoft\Badaso\Models\Permission;

class BadasoApiCrudManagementTest extends TestCase
{
    private $KEY_LIST_CREATE_TABLES = 'LIST_CREATE_TABLES';
    private $KEY_LIST_CREATE_EMPTY_TABLES = 'LIST_CREATE_EMPTY_TABLES';
    private $KEY_DATA_TABLE_CRUD_MANAGEMENT_LOG = 'DATA_TABLE_CRUD_MANAGEMENT_LOG';
    private $KEY_DATA_EMPTY_TABLE_CRUD_MANAGEMENT_LOG = 'DATA_EMPTY_TABLE_CRUD_MANAGEMENT_LOG';
    private $KEY_DATA_RESPONSE_ADD_CRUD_MANAGEMENT = 'DATA_RESPONSE_ADD_CRUD_MANAGEMENT';
    private $KEY_DATA_RESPONSE_READ_TABLE_ENTITY = 'KEY_DATA_RESPONSE_READ_TABLE_ENTITY';
    private $KEY_DATA_RESPONSE_READ_EMPTY_TABLE_ENTITY = 'KEY_DATA_RESPONSE_READ_EMPTY_TABLE_ENTITY';
    private $KEY_DATA_ADD_ENTITY = 'KEY_DATA_ADD_ENTITY';
    private $KEY_EMPTY_DATA_ADD_ENTITY = 'KEY_EMPTY_DATA_ADD_ENTITY';
    private $TABLE_TEST_PREFIX = 'test_table_';
    private $TABLE_TEST_EMPTY_VALUE_PREFIX = 'test_empty_table_';
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
                    'example' => "[\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png\',\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png\']",
                    'example_update' => "[\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png\',\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png\']",
                ],
            ];
    }

    private function getEmptyValueFields(): array
    {
        return
            $field_name = [
                [
                    'badaso_type' => 'text',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'text',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'email',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'email@example.com',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'password',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'password',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'textarea',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'textarea',
                    'example_update' => '',
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
                    'example_update' => [],
                ],
                [
                    'badaso_type' => 'search',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'search',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'number',
                    'schema_type' => 'integer',
                    'details' => json_encode((object) []),
                    'example' => 1,
                    'example_update' => null,
                ],
                [
                    'badaso_type' => 'url',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'https://badaso-docs.uatech.co.id',
                    'example_update' => '',
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
                    'example' => '',
                    'example_update' => 'example_2',
                ],
                [
                    'badaso_type' => 'editor',
                    'schema_type' => 'text',
                    'details' => json_encode((object) []),
                    'example' => 'editor',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'code',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'code',
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'hidden',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'hidden',
                    'example_update' => '',
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
                    'example_update' => '',
                ],
                [
                    'badaso_type' => 'upload_image',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png',
                    'example_update' => '',
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
                    'example' => [],
                    'example_update' => [
                        'example_2',
                        'example_3',
                    ],
                ],
                [
                    'badaso_type' => 'upload_file',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => '',
                    'example_update' => 'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619581504968_uasoft.png',
                ],
                [
                    'badaso_type' => 'upload_image_multiple',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => "['https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png','https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png']",
                    'example_update' => [],
                ],
                [
                    'badaso_type' => 'upload_file_multiple',
                    'schema_type' => 'string',
                    'details' => json_encode((object) []),
                    'example' => "[\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png\',\'https://badaso-web.s3-ap-southeast-1.amazonaws.com/files/shares/1619582634819_badaso.png\']",
                    'example_update' => '[]',
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

    private function createTestEmptyValueTables(int $max_count_table_generate)
    {
        $table_names = [];
        for ($index = 1; $index <= $max_count_table_generate; $index++) {
            $table_name = "{$this->TABLE_TEST_EMPTY_VALUE_PREFIX}{$index}";
            if (! Schema::hasTable($table_name)) {
                Schema::create($table_name, function (Blueprint $table) use ($index, $table_names) {
                    $table->id();

                    foreach ($this->getEmptyValueFields() as $key => ['badaso_type' => $badaso_type, 'schema_type' => $schema_type]) {
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
        CallHelperTest::setCache($this->KEY_LIST_CREATE_EMPTY_TABLES, $table_names);
    }

    private function deleteAllTestTables()
    {
        $table_names = collect(CallHelperTest::getCache($this->KEY_LIST_CREATE_TABLES))->reverse();
        foreach ($table_names as $key => $table_names) {
            Schema::dropIfExists($table_names);
        }

        $table_empty_names = collect(CallHelperTest::getCache($this->KEY_LIST_CREATE_EMPTY_TABLES))->reverse();
        foreach ($table_empty_names as $key => $table_empty_names) {
            Schema::dropIfExists($table_empty_names);
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

        // init create all tables empty value testing
        $this->createTestEmptyValueTables(10);
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
                    'required' => 1,
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
                    'required' => 1,
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
                    $row['relationType'] = ['has_one', 'has_many'][rand(0, 1)];
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
            if (rand(0, 1) || $table_names[0]) {
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
                'name' => $table_name,
                'slug' => $table_name,
                'displayNameSingular' => $table_label,
                'displayNamePlural' => $table_label,
                'icon' => 'add',
                'modelName' => $model,
                'policyName' => '',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'generatePermissions' => rand(0, 1),
                'createSoftDelete' => rand(0, 1),
                'serverSide' => rand(0, 1),
                'details' => json_encode((object) []),
                'controller' => $controller,
                'orderColumn' => '',
                'orderDisplayColumn' => '',
                'orderDirection' => '',
                'notification' => array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 3)),
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

    public function testAddTableCrudMultiRelationEntity()
    {
        $first_table = 'multiple_table_1';
        $second_table = 'multiple_table_2';

        $table_1 = [
            'table' => $first_table,
            'rows' => [
                0 => [
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
                1 => [
                    'id' => '8b01b738-377b-4782-898f-c4c46722bf23',
                    'fieldName' => 'field1',
                    'fieldType' => 'text',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => '',
                ],
                2 => [
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
                3 => [
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
        $table_2 = [
            'table' => $second_table,
            'rows' => [
                0 => [
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
                1 => [
                    'id' => 'd6dfe842-9e57-4b2e-ae2a-4e2e8fb5d5ab',
                    'fieldName' => 'id_relation1_field1',
                    'fieldType' => 'text',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => '',
                ],
                2 => [
                    'id' => 'a9f49e8e-ad45-441c-9839-df506f8bfaf2',
                    'fieldName' => 'id_relation2_field1',
                    'fieldType' => 'text',
                    'fieldLength' => null,
                    'fieldNull' => false,
                    'fieldAttribute' => false,
                    'fieldIncrement' => false,
                    'fieldIndex' => null,
                    'fieldDefault' => '',
                ],
                3 => [
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
                4 => [
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
        $crud_table_1 = [
            'name' => $first_table,
            'slug' => 'multiple-table-1',
            'displayNameSingular' => 'multiple table 1',
            'displayNamePlural' => 'multiple table 1',
            'icon' => '',
            'modelName' => '',
            'policyName' => '',
            'description' => '',
            'generatePermissions' => true,
            'createSoftDelete' => false,
            'serverSide' => false,
            'details' => '',
            'controller' => '',
            'orderColumn' => '',
            'orderDisplayColumn' => '',
            'orderDirection' => '',
            'notification' => [],
            'rows' => [
                0 => [
                    'field' => 'id',
                    'type' => 'number',
                    'displayName' => 'Id',
                    'required' => true,
                    'browse' => true,
                    'read' => true,
                    'edit' => true,
                    'add' => true,
                    'delete' => true,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
                1 => [
                    'field' => 'field1',
                    'type' => 'textarea',
                    'displayName' => 'Field1',
                    'required' => true,
                    'browse' => true,
                    'read' => true,
                    'edit' => true,
                    'add' => true,
                    'delete' => true,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
                2 => [
                    'field' => 'created_at',
                    'type' => 'datetime',
                    'displayName' => 'Created At',
                    'required' => false,
                    'browse' => true,
                    'read' => true,
                    'edit' => false,
                    'add' => false,
                    'delete' => false,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
                3 => [
                    'field' => 'updated_at',
                    'type' => 'datetime',
                    'displayName' => 'Updated At',
                    'required' => false,
                    'browse' => true,
                    'read' => true,
                    'edit' => false,
                    'add' => false,
                    'delete' => false,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
            ],
        ];

        $crud_table_2 = [
            'name' => $second_table,
            'slug' => 'multiple-table-2',
            'displayNameSingular' => 'multiple table 2',
            'displayNamePlural' => 'multiple table 2',
            'icon' => '',
            'modelName' => '',
            'policyName' => '',
            'description' => '',
            'generatePermissions' => true,
            'createSoftDelete' => false,
            'serverSide' => false,
            'details' => '',
            'controller' => '',
            'orderColumn' => '',
            'orderDisplayColumn' => '',
            'orderDirection' => '',
            'notification' => [],
            'rows' => [
                0 => [
                    'field' => 'id',
                    'type' => 'number',
                    'displayName' => 'Id',
                    'required' => true,
                    'browse' => true,
                    'read' => true,
                    'edit' => true,
                    'add' => true,
                    'delete' => true,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
                1 => [
                    'field' => 'id_relation1_field1',
                    'type' => 'relation',
                    'displayName' => 'Id Relation1 Field1',
                    'required' => true,
                    'browse' => true,
                    'read' => true,
                    'edit' => true,
                    'add' => true,
                    'delete' => true,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                    'relationType' => 'belongs_to',
                    'destinationTable' => $first_table,
                    'destinationTableColumn' => 'id',
                    'destinationTableDisplayColumn' => 'field1',
                ],
                2 => [
                    'field' => 'id_relation2_field1',
                    'type' => 'relation',
                    'displayName' => 'Id Relation2 Field1',
                    'required' => true,
                    'browse' => true,
                    'read' => true,
                    'edit' => true,
                    'add' => true,
                    'delete' => true,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                    'relationType' => 'belongs_to',
                    'destinationTable' => $first_table,
                    'destinationTableColumn' => 'id',
                    'destinationTableDisplayColumn' => 'field1',
                ],
                3 => [
                    'field' => 'created_at',
                    'type' => 'datetime',
                    'displayName' => 'Created At',
                    'required' => false,
                    'browse' => true,
                    'read' => true,
                    'edit' => false,
                    'add' => false,
                    'delete' => false,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
                4 => [
                    'field' => 'updated_at',
                    'type' => 'datetime',
                    'displayName' => 'Updated At',
                    'required' => false,
                    'browse' => true,
                    'read' => true,
                    'edit' => false,
                    'add' => false,
                    'delete' => false,
                    'details' => '{}',
                    'order' => 1,
                    'setRelation' => false,
                ],
            ],
        ];

        $add_table = [$table_1, $table_2];

        // add table
        foreach ($add_table as $key => $request_data_table) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/add'), $request_data_table);
            $response->assertSuccessful();
        }

        $add_crud_table = [$crud_table_1, $crud_table_2];
        // add crud management
        foreach ($add_crud_table as $key => $request_data_crud_table) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/crud/add'), $request_data_crud_table);
            // $response->assertSuccessful();
        }
    }

    public function testAddTableManyToMany()
    {
        $name_table = ['table_primary', 'table_destination', 'table_relation'];
        foreach ($name_table as $key => $table) {
            $table = [
                'table' => $table,
                'rows' => [
                    0 => [
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
                    1 => [
                        'id' => '8b01b738-377b-4782-898f-c4c46722bf23',
                        'fieldName' => 'name',
                        'fieldType' => 'text',
                        'fieldLength' => null,
                        'fieldNull' => false,
                        'fieldAttribute' => false,
                        'fieldIncrement' => false,
                        'fieldIndex' => null,
                        'fieldDefault' => '',
                    ],
                    2 => [
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
                    3 => [
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
            if ($table['table'] == 'table_relation') {
                for ($i = 0; $i < 2; $i++) {
                    $field[$i] = [
                        'id' => $name_table[$i].'_id',
                        'fieldName' => $name_table[$i].'_id',
                        'fieldType' => 'bigint',
                        'fieldLength' => null,
                        'fieldNull' => false,
                        'fieldAttribute' => true,
                        'fieldIncrement' => false,
                        'fieldIndex' => 'foreign',
                        'fieldDefault' => null,
                    ];
                    unset($table['rows'][1]);
                    array_push($table['rows'], $field[$i]);
                }
                $table['relations'] = [
                    $name_table[1].'_id' => [
                        'source_field' => $name_table[1].'_id',
                        'target_table' => $name_table[1],
                        'target_field' => 'id',
                        'on_delete' => 'cascade',
                        'on_update' => 'restrict',
                    ],
                    $name_table[0].'_id' => [
                        'source_field' => $name_table[0].'_id',
                        'target_table' => $name_table[0],
                        'target_field' => 'id',
                        'on_delete' => 'cascade',
                        'on_update' => 'restrict',
                    ],
                ];
            }
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/add'), $table);
            $response->assertSuccessful();
        }
        foreach ($name_table as $key => $crud_table) {
            $crud_table = [
                'name' => $crud_table,
                'slug' => 'table-'.$key + 1,
                'displayNameSingular' => $crud_table,
                'displayNamePlural' => $crud_table,
                'icon' => '',
                'modelName' => '',
                'policyName' => '',
                'description' => '',
                'generatePermissions' => true,
                'createSoftDelete' => false,
                'serverSide' => false,
                'details' => '',
                'controller' => '',
                'orderColumn' => '',
                'orderDisplayColumn' => '',
                'orderDirection' => '',
                'notification' => [],
                'rows' => [
                    0 => [
                        'field' => 'id',
                        'type' => 'number',
                        'displayName' => 'Id',
                        'required' => true,
                        'browse' => false,
                        'read' => false,
                        'edit' => false,
                        'add' => false,
                        'delete' => false,
                        'details' => '{}',
                        'order' => 1,
                        'setRelation' => false,
                    ],
                    1 => [
                        'field' => 'name',
                        'type' => 'text',
                        'displayName' => 'name',
                        'required' => true,
                        'browse' => true,
                        'read' => true,
                        'edit' => true,
                        'add' => true,
                        'delete' => true,
                        'details' => '{}',
                        'order' => 1,
                        'setRelation' => false,
                        'relationType' => 'null',
                        'destinationTable' => 'null',
                        'destinationTableColumn' => 'null',
                        'destinationTableDisplayColumn' => 'null',
                    ],
                    2 => [
                        'field' => 'created_at',
                        'type' => 'datetime',
                        'displayName' => 'Created At',
                        'required' => false,
                        'browse' => true,
                        'read' => true,
                        'edit' => false,
                        'add' => false,
                        'delete' => false,
                        'details' => '{}',
                        'order' => 1,
                        'setRelation' => false,
                    ],
                    3 => [
                        'field' => 'updated_at',
                        'type' => 'datetime',
                        'displayName' => 'Updated At',
                        'required' => false,
                        'browse' => true,
                        'read' => true,
                        'edit' => false,
                        'add' => false,
                        'delete' => false,
                        'details' => '{}',
                        'order' => 1,
                        'setRelation' => false,
                    ],
                ],
            ];
            $relation_field = [
                'field' => 'table_relation',
                'type' => 'relation',
                'displayName' => 'table 1 table 2 relation',
                'required' => true,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => true,
                'details' => '{}',
                'order' => 1,
                'setRelation' => false,
                'relationType' => 'belongs_to_many',
                'destinationTable' => $name_table[1],
                'destinationTableColumn' => 'id',
                'destinationTableDisplayColumn' => 'name',
            ];
            if ($crud_table['name'] == $name_table[0]) {
                array_push($crud_table['rows'], $relation_field);
            }

            if ($crud_table['name'] != 'table_relation') {
                $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/crud/add'), $crud_table);
                $response->assertSuccessful();
            }
        }
    }

    public function testEntityManytoMany()
    {
        $table_lists = ['table_relation', 'table_destination', 'table_primary'];
        $name_table = ['table-2', 'table-1'];
        $data_table_destination = [
            'data' => [
                'name' => 'option 1',
            ],
        ];
        $data_table_primary = [
            'data' => [
                'name' => 'lorem ipsum',
                'table_relation' => [2, 3],
            ],
        ];

        foreach ($name_table as $key => $table) {
            if ($table == 'table-2') {
                for ($i = 1; $i < 4; $i++) {
                    $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/entities/'.$table.'/add'), [
                        'data' => [
                            'name' => 'option '.$i,
                        ],
                    ]);
                }
            } else {
                $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/entities/'.$table.'/add'), $data_table_primary);
                $response->assertSuccessful();
            }
        }

        // browse
        $response = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/entities/'.$table));
        $response->assertSuccessful();
        $data_browse = $response['data']['data'][0];

        // edit
        $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/entities/'.$table.'/edit'), [
            'data' => [
                'id' => $data_browse['id'],
                'name' => 'lorem ipsum',
                'table_relation' => [1, 2],
            ],
        ]);
        $response->assertSuccessful();

        // delete
        $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/entities/'.$table.'/delete'), [
            'slug' => $table,
            'data' => [
                [
                    'field' => 'id',
                    'value' => $data_browse['id'],
                ],
            ],
        ]);

        $response_crud_table = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud?'));
        $response_crud_table = $response_crud_table['data'];
        foreach ($response_crud_table['tablesWithCrudData'] as $key => $value_response_crud_table) {
            if (in_array($value_response_crud_table['tableName'], $table_lists)) {
                if ($value_response_crud_table['tableName'] != $table_lists[0]) {
                    $ids_list_table[$key] = [
                        'id' => $value_response_crud_table['crudData']['id'],
                    ];
                }
            }
        }
        // Delete table
        foreach ($table_lists as $key => $table) {
            if ($table != $table_lists[0]) {
                foreach ($ids_list_table as $key => $id) {
                    $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/crud/delete'), $id);
                }
            }
            $deleted = Schema::dropIfExists($table);
        }
    }

    public function testAddEntityMultiRelation()
    {
        $first_table = 'multiple_table_1';
        $second_table = 'multiple_table_2';
        $list_table = [$first_table, $second_table];

        // set data for table 1
        $fill_table_1 = ['student', 'teacher'];
        $add_fill_table_1 = [];
        for ($i = 0; $i < 2; $i++) {
            $add_fill_table_1[$i] = [
                'data' => [
                    'field1' => $fill_table_1[$i],
                ],
            ];
        }

        // set data for table 2
        $add_fill_table_2 = [
            'data' => [
                'id_relation1_field1' => 1,
                'id_relation2_field1' => 2,
            ],
        ];

        // add fill table 1
        $response_crud_table = CallHelperTest::withAuthorizeBearer($this)->json('GET', CallHelperTest::getUrlApiV1Prefix('/crud?'));
        $response_crud_table = $response_crud_table['data'];
        foreach ($response_crud_table['tablesWithCrudData'] as $key => $value_response_crud_table) {
            foreach ($list_table as $key_add_table => $value_add_table) {
                if (in_array($value_add_table, $value_response_crud_table)) {
                    $ids_list_table[$key_add_table] = [
                        'id' => $value_response_crud_table['crudData']['id'],
                    ];
                    if ($value_add_table == $list_table[0]) {
                        foreach ($add_fill_table_1 as $key => $request_data_fill_crud_table_1) {
                            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/entities/multiple-table-1/add'), $request_data_fill_crud_table_1);
                            $response->assertSuccessful();
                            $response_table_1 = $response['data'];
                            foreach ($fill_table_1 as $key => $value) {
                                if (in_array($value, $response_table_1)) {
                                    $this->assertTrue($response_table_1['field1'] == $value);
                                }
                            }
                        }
                    }
                }
            }
        }

        // add fill table 2
        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/entities/multiple-table-2/add'), $add_fill_table_2);
        $response->assertSuccessful();
        $response_table_2 = $response['data'];
        foreach ($response_table_2 as $key => $value) {
            if ($key == 'idRelation1Field1') {
                $this->assertTrue($value == $add_fill_table_2['data']['id_relation1_field1']);
            }
            if ($key == 'idRelation2Field1') {
                $this->assertTrue($value == $add_fill_table_2['data']['id_relation2_field1']);
            }
        }
        // delete crud management
        foreach ($ids_list_table as $key => $value_ids_list_table) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/crud/delete'), $value_ids_list_table);
            $response->assertSuccessful();
        }

        // delete table
        foreach ($list_table as $key => $request_data_table) {
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/database/delete'), ['table' => $request_data_table]);
            $response->assertSuccessful();
        }

        // Delete Migration
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('GET', CallHelperTest::getUrlApiV1Prefix('/database/migration/browse'));

        $response = $response->json('data');
        $migration_name = [];
        for ($i = count($response) - 8; $i < count($response); $i++) {
            $migration_name[] = $response[$i]['migration'];
        }
        $response = CallHelperTest::withAuthorizeBearer($this)
            ->json('POST', CallHelperTest::getUrlApiV1Prefix('/database/migration/delete'), [
                'file_name' => $migration_name,
            ]);
        $response->assertSuccessful();
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

    public function testAddReadEmptyTableCrudManagement()
    {
        // create crud management empty value tables
        $table_names = CallHelperTest::getCache($this->KEY_LIST_CREATE_EMPTY_TABLES);
        $const_fields = $this->getEmptyValueFields();
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
                    'required' => 1,
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
                    'required' => 0,
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
                    $row['relationType'] = ['has_one', 'has_many'][rand(0, 1)];
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
            if (rand(0, 1) || $table_names[0]) {
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
                'name' => $table_name,
                'slug' => $table_name,
                'displayNameSingular' => $table_label,
                'displayNamePlural' => $table_label,
                'icon' => 'add',
                'modelName' => $model,
                'policyName' => '',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'generatePermissions' => rand(0, 1),
                'createSoftDelete' => rand(0, 1),
                'serverSide' => rand(0, 1),
                'details' => json_encode((object) []),
                'controller' => $controller,
                'orderColumn' => '',
                'orderDisplayColumn' => '',
                'orderDirection' => '',
                'notification' => array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 3)),
                'isMaintenance' => rand(0, 1),
                'rows' => $rows,
            ];
            $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/crud/add'), $request_body);
            $response->assertSuccessful();

            // save logs
            $data_table_crud_management_log[$index_table_name]['request_body'] = $request_body;
            $data_response_add_crud_management[] = $response->json('data');
        }

        CallHelperTest::setCache($this->KEY_DATA_EMPTY_TABLE_CRUD_MANAGEMENT_LOG, $data_table_crud_management_log);

        // read data crud management
        $data_table_crud_management_logs = CallHelperTest::getCache($this->KEY_DATA_EMPTY_TABLE_CRUD_MANAGEMENT_LOG);
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

        CallHelperTest::setCache($this->KEY_DATA_RESPONSE_READ_EMPTY_TABLE_ENTITY, $response_read_table_entities);
    }

    public function testAddEditEmptyTableEntity()
    {
        // add and edit data entity
        $tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_EMPTY_TABLES);
        $first_table = $tables[0];

        $get_response_read_table_entities = CallHelperTest::getCache($this->KEY_DATA_RESPONSE_READ_EMPTY_TABLE_ENTITY);
        $fields = [];
        foreach ($this->getEmptyValueFields() as $key => $value) {
            $fields[$value['badaso_type']] = $value;
        }

        $data_add_entities = [];
        foreach ($get_response_read_table_entities as $table_empty => $entities) {
            $entities = collect($entities)->filter(function ($entity) {
                return $entity['add'];
            })->values();

            $data_add_entities[$table_empty] = [];
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
                $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix("/entities/{$table_empty}/add"), [
                    'data' => $data,
                ]);

                $response->assertSuccessful();

                // update
                $id = $response->json('data.id');
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
                $response = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix("/entities/{$table_empty}/edit"), [
                    'data' => $data,
                ]);
                $response->assertSuccessful();

                $data_add_entities[$table_empty][] = $response->json('data');
            }
        }

        CallHelperTest::setCache(
            $this->KEY_EMPTY_DATA_ADD_ENTITY,
            $data_add_entities
        );
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

    public function testBrowseIsPublicPermissionEntity()
    {
        //  create table
        $table_name = 'table_public';
        Schema::dropIfExists($table_name);
        if (! Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) {
                $table->id();
                $table->text('name')->nullable();
                $table->bigInteger('user_id')->nullable()->unsigned();
                $table->foreign('user_id')->references('id')->on(config('badaso.database.prefix').'users')->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        $table_names[] = $table_name;

        // add crud management
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
                'field' => 'name',
                'type' => 'text',
                'displayName' => 'Name',
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
                'field' => 'user_id',
                'type' => 'data_identifier',
                'displayName' => 'User Id',
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
        $table_label = ucwords(str_replace(['_'], ' ', $table_name));
        $request_body = [
            'name' => $table_name,
            'slug' => $table_name,
            'displayNameSingular' => $table_label,
            'displayNamePlural' => $table_label,
            'icon' => 'add',
            'modelName' => '',
            'policyName' => '',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'generatePermissions' => 1,
            'createSoftDelete' => rand(0, 1),
            'serverSide' => rand(0, 1),
            'details' => json_encode((object) []),
            'controller' => '',
            'orderColumn' => '',
            'orderDisplayColumn' => '',
            'orderDirection' => '',
            'notification' => array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 3)),
            'isMaintenance' => rand(0, 1),
            'rows' => $rows,
        ];
        $response = CallHelperTest::withAuthorizeBearer($this)->json('POST', CallHelperTest::getUrlApiV1Prefix('/crud/add'), $request_body);
        $response->assertSuccessful();

        // edit permission IsPublic Permission
        $permissions = Permission::where('key', 'browse_'.$table_name)->get();
        foreach ($permissions as $key => $value) {
            $permission_id = $value->id;
        }
        $request_data = [
            'always_allow' => false,
            'description' => Str::uuid(),
            'is_public' => true,
            'key' => 'browse_'.$table_name,
            'id' => $permission_id,
        ];
        $response_permission = CallHelperTest::withAuthorizeBearer($this)->json('PUT', CallHelperTest::getUrlApiV1Prefix('/permissions/edit'), $request_data);
        $response_permission->assertSuccessful();

        // browse data entity IsPublic
        $response_entity = $this->json('GET', CallHelperTest::getUrlApiV1Prefix('/table/read'), [
            'table' => $request_body['slug'],
        ]);
        $response_entity->assertSuccessful();

        // delete crud management and data type
        $data_types = DataType::whereIn('name', $table_names)->get();
        foreach ($data_types as $key => $data_type) {
            $id = $data_type->id;
            $response = CallHelperTest::withAuthorizeBearer($this)->json('DELETE', CallHelperTest::getUrlApiV1Prefix('/crud/delete'), [
                'id' => $id,
            ]);
            $response->assertSuccessful();
            $data_type->delete();
        }

        // delete table public
        Schema::dropIfExists($table_name);
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
                'name' => $table_name,
                'slug' => $table_name,
                'displayNameSingular' => $table_label.'(update)',
                'displayNamePlural' => $table_label.'(update)',
                'icon' => 'add',
                'modelName' => $model,
                'policyName' => '',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. (update)',
                'generatePermissions' => rand(0, 1),
                'createSoftDelete' => rand(0, 1),
                'serverSide' => rand(0, 1),
                'details' => json_encode((object) []),
                'controller' => $controller,
                'orderColumn' => '',
                'orderDisplayColumn' => '',
                'orderDirection' => '',
                'notification' => array_slice(['onCreate', 'onDelete', 'onUpdate', 'onRead'], 0, rand(0, 0)),
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
        $empty_tables = CallHelperTest::getCache($this->KEY_LIST_CREATE_EMPTY_TABLES);
        $collect_tables = [$tables, $empty_tables];
        foreach ($collect_tables as $key => $item_tables) {
            $data_types = DataType::whereIn('name', $item_tables)->get();
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
            $data_types = DataType::whereIn('name', $item_tables)->get();
            $this->assertEmpty($data_types);
        }
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

    public function testUploadImageFile()
    {
        // Upload file not valid mimeType
        $file_php = UploadedFile::fake()->create('document.php', 20);
        $file =
            [
                'type' => 'file',
                'upload' => $file_php,
            ];
        $response = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/file/upload/lfm'), $file);
        $message = json_decode($response['message'], true);
        $this->assertArrayHasKey('error', $message['original']);

        // Upload file valid mimeType
        $file_pdf = UploadedFile::fake()->create('document.pdf', 200);
        $file = [
            'type' => 'file',
            'upload' => $file_pdf,
        ];
        $response = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/file/upload/lfm'), $file);
        $message = $response['data'];
        $this->assertNull($response['errors']);
        $this->assertArrayHasKey('uploaded', $message['original']);

        // Upload image valid mimeType
        $image_file = UploadedFile::fake()->image('image.jpg');
        $image = [
            'type' => 'image',
            'upload' => $image_file,
        ];
        $response = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/file/upload/lfm'), $image);
        $message = $response['data'];
        $this->assertNull($response['errors']);
        $this->assertArrayHasKey('uploaded', $message['original']);
    }
}
