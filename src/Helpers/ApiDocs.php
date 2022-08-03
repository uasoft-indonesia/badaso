<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Filesystem\Filesystem;
use Uasoft\Badaso\Models\Permission;

class ApiDocs
{
    const PARAMETER = <<<'TXT'
      *      @OA\Parameter(
      *          name="%s",
      *          required=true,
      *          in="path",
      *          @OA\Schema(
      *              type="%s"
      *          )
      *      )
    TXT;

    const ITEMS = <<<'TXT'
    {"field":"%s","value":"%s"}
    TXT;

    const ITEM = <<<'TXT'
    "%s":"%s"
    TXT;

    const ITEM_WRAPPER = <<<'TXT'
    {%s}
    TXT;

    const PROPERTY = <<<'TXT'
      *                         @OA\Property(type="%s", property="%s")
    TXT;

    const PHP_WRAPPER = <<<'TXT'
    <?php

    %s
    TXT;

    const BEARER_AUTH = <<<'TXT'
    {"bearerAuth": {}}
    TXT;

    const BROWSE = <<<'TXT'
    /**
      * @OA\Get(
      *      path="/v1/entities/%s",
      *      operationId="browse%s",
      *      tags={"%s"},
      *      summary="Browse %s",
      *      description="Returns list of %s",
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const READ = <<<'TXT'
    /**
      * @OA\Get(
      *      path="/v1/entities/%s/read?slug=%s&id={id}",
      *      operationId="read%s",
      *      tags={"%s"},
      *      summary="Get %s based on id",
      *      description="Returns %s based on id",
    %s,
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const ADD = <<<'TXT'
    /**
      * @OA\Post(
      *      path="/v1/entities/%s/add",
      *      operationId="add%s",
      *      tags={"%s"},
      *      summary="Insert new %s",
      *      description="Insert new %s into database",
      *      @OA\RequestBody(
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             @OA\Schema(
      *                 @OA\Property(
      *                     property="data",
      *                     type="object",
      *                     example={%s},
      *                 ),
      *             )
      *         )
      *      ),
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const EDIT = <<<'TXT'
    /**
      * @OA\Put(
      *      path="/v1/entities/%s/edit",
      *      operationId="edit%s",
      *      tags={"%s"},
      *      summary="Edit an existing %s",
      *      description="Edit an existing %s",
      *      @OA\RequestBody(
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             @OA\Schema(
      *                 @OA\Property(
      *                     property="data",
      *                     type="object",
      *                     example={%s},
      *                ),
      *             )
      *         )
      *     ),
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const DELETE = <<<'TXT'
    /**
      * @OA\Delete(
      *      path="/v1/entities/%s/delete",
      *      operationId="delete%s",
      *      tags={"%s"},
      *      summary="Delete one record of %s",
      *      description="Delete one record of %s",
      *      @OA\RequestBody(
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             @OA\Schema(
      *                 @OA\Property(
      *                     property="slug",
      *                     example="%s",
      *                     type="string"
      *                 ),
      *                 @OA\Property(
      *                     property="data",
      *                     type="array",
      *                     @OA\Items(
      *                         type="object",
      *                         @OA\Property(type="string", property="field", default="%s"),
      *                         @OA\Property(type="%s", property="value", example="%s"),
      *                     ),
      *                ),
      *             )
      *         )
      *     ),
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const DELETE_MULTIPLE = <<<'TXT'
    /**
      * @OA\Delete(
      *      path="/v1/entities/%s/delete-multiple",
      *      operationId="deleteMultiple%s",
      *      tags={"%s"},
      *      summary="Delete multiple record of %s",
      *      description="Delete multiple record of %s",
      *      @OA\RequestBody(
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             @OA\Schema(
      *                 @OA\Property(
      *                     property="slug",
      *                     example="%s",
      *                     type="string"
      *                 ),
      *                 @OA\Property(
      *                     property="data",
      *                     type="array",
      *                     @OA\Items(
      *                         type="object",
      *                         @OA\Property(type="string", property="field", default="%s"),
      *                         @OA\Property(type="{}", property="value", example="%s"),
      *                     ),
      *                ),
      *             )
      *         )
      *     ),
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    const SORT = <<<'TXT'
    /**
      * @OA\Put(
      *      path="/v1/entities/%s/sort",
      *      operationId="sort%s",
      *      tags={"%s"},
      *      summary="Sort existing %s",
      *      description="Sort existing %s",
      *      @OA\RequestBody(
      *         @OA\MediaType(
      *             mediaType="application/json",
      *             @OA\Schema(
      *                 @OA\Property(
      *                     property="slug",
      *                     example="%s",
      *                     type="string"
      *                 ),
      *                 @OA\Property(
      *                     property="data",
      *                     type="array",
      *                     example={%s},
      *                     @OA\Items(
      *                         type="object",
    %s,
      *                     ),
      *                ),
      *             )
      *         )
      *     ),
      *      @OA\Response(response=200, description="Successful operation"),
      *      @OA\Response(response=400, description="Bad request"),
      *      @OA\Response(response=401, description="Unauthorized"),
      *      @OA\Response(response=402, description="Payment Required"),
      *      security={
      *          %s
      *      }
      * )
      *
      */
    TXT;

    public static function getFilePath($table_name)
    {
        $api_docs_path = app_path('Http/Swagger/swagger_models/');

        return $api_docs_file = $api_docs_path.$table_name.'.php';
    }

    public static function getAuthorize($action, $table_name, $permission)
    {
        $authorize = self::BEARER_AUTH;
        $permission_key = "{$action}_{$table_name}";
        $permission_table = $permission->where('key', $permission_key)->first();
        if (isset($permission_table)) {
            if ($permission_table->is_public) {
                $authorize = '';
            }
        }

        return $authorize;
    }

    public static function getStub($table_name, $data_rows, $data_type)
    {
        $edit = $add = $id_column = $stub = [];
        foreach ($data_rows as $key => $row) {
            if ($row['edit']) {
                $edit[] = $row;
            }
            if ($row['add']) {
                $add[] = $row;
            }
            if ($row['delete']) {
                $delete[] = $row;
            }
            if ($row['field'] === 'id') {
                $id_column = $row;
            }
        }

        $table_name_data_type = $data_type->name;
        $permission = Permission::where('table_name', $table_name_data_type)->get();

        $stub['browse'] = sprintf(
            self::BROWSE,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            self::getAuthorize('browse', $table_name_data_type, $permission),
        );

        $stub['read'] = sprintf(
            self::READ,
            $data_type->slug,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            sprintf(self::PARAMETER, $id_column['field'], self::getColumnType($id_column['type'])),
            self::getAuthorize('read', $table_name_data_type, $permission),
        );

        $stub['add'] = sprintf(
            self::ADD,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            self::getColumns($add),
            self::getAuthorize('add', $table_name_data_type, $permission),
        );

        $stub['edit'] = sprintf(
            self::EDIT,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            self::getColumns($edit),
            self::getAuthorize('edit', $table_name_data_type, $permission),
        );

        $stub['delete'] = sprintf(
            self::DELETE,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            $data_type->slug,
            $id_column['field'],
            self::getColumnType($id_column['type']),
            self::getColumnExample($id_column['type']),
            self::getAuthorize('delete', $table_name_data_type, $permission),
        );

        $stub['delete_multiple'] = sprintf(
            self::DELETE_MULTIPLE,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            $data_type->slug,
            $id_column['field'] = 'ids',
            self::getColumnExample($id_column['type']).','.self::getColumnExample($id_column['type']),
            self::getAuthorize('delete_multiple', $table_name_data_type, $permission),
        );

        $stub['sort'] = sprintf(
            self::SORT,
            $data_type->slug,
            str_replace(' ', '', $data_type->display_name_singular),
            $data_type->slug,
            $data_type->display_name_singular,
            $data_type->display_name_singular,
            $data_type->slug,
            self::getColumnsSort($data_rows).', '.self::getColumnsSort($data_rows),
            self::getProperty($data_rows),
            self::getAuthorize('sort', $table_name_data_type, $permission),
        );

        $stub = implode(PHP_EOL.PHP_EOL, $stub);

        return sprintf(self::PHP_WRAPPER, $stub);
    }

    private static function getColumnType($type)
    {
        switch ($type) {
            case 'number':
                return 'integer';
                break;
            default:
                return 'string';
                break;
        }
    }

    private static function getColumnExample($type)
    {
        switch ($type) {
            case 'number':
                return '123';
                break;
            case 'datetime':
                return '2021-01-01T00:00:00.000Z';
                break;
            case 'relation':
                return null;
                break;
            default:
                return 'Abc';
                break;
        }
    }

    private static function getColumns($rows)
    {
        $items = [];
        foreach ($rows as $key => $row) {
            $items[] = sprintf(
                self::ITEM,
                CaseConvert::camel($row['field']),
                self::getColumnExample($row['type']),
            );
        }

        return $items = implode(', ', $items);
    }

    private static function getColumnsSort($rows)
    {
        $item = [];
        foreach ($rows as $key => $row) {
            $item[] = sprintf(
                self::ITEM,
                CaseConvert::camel($row['field']),
                self::getColumnExample($row['type']),
            );
        }

        return $item = sprintf(self::ITEM_WRAPPER, implode(', ', $item));
    }

    private static function getProperty($rows)
    {
        $property = [];
        foreach ($rows as $key => $row) {
            $property[] = sprintf(
                self::PROPERTY,
                self::getColumnType($row['type']),
                CaseConvert::camel($row['field']),
            );
        }

        return $property = implode(', '.PHP_EOL, $property);
    }

    public static function generateAPIDocs($table_name, $data_rows, $data_type)
    {
        $filesystem = new Filesystem();
        $file_path = self::getFilePath($table_name);
        $stub = self::getStub($table_name, $data_rows, $data_type);

        $status_put_file = $filesystem->put($file_path, $stub);

        return $status_put_file;
    }
}
