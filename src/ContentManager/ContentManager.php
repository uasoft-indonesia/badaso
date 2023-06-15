<?php

namespace Uasoft\Badaso\ContentManager;

use Uasoft\Badaso\Models\DataType;

class ContentManager
{
    /** @var ContentGenerator */
    private $content_generator;

    /**
     * ContentManager constructor.
     */
    public function __construct(ContentGenerator $content_generator)
    {
        $this->content_generator = $content_generator;
    }

    /**
     * Repack Content Data.
     *
     * @param  $data
     */
    public function repackContentData($data): array
    {
        $data_array = [];
        if (! empty($data)) {
            foreach ($data as $row) {
                $row_array = [];
                foreach ($row as $column_name => $column_value) {
                    if ($column_name === 'details' || $column_name === 'relation') {
                        $column_value = (is_array($column_value) || is_object($column_value)) ? json_encode($column_value) : $column_value;
                    }
                    $row_array[$column_name] = $column_value;

                    if ($column_name === 'id') {
                        unset($row_array[$column_name]);
                    }

                    if ($column_name === 'data_type_id') {
                        $row_array[$column_name] = '#dataTypeId';
                    }
                    if ($column_name === 'foreign_key') {
                        $row_array[$column_name] = '#dataTypeId';
                    }
                }
                $data_array[] = $row_array;
            }
        }

        return $data_array;
    }

    /**
     * Populate Content To Stub File.
     *
     * @return mixed|string
     */
    public function populateContentToStubFile(
        string $stub,
        DataType $data_type,
        string $suffix
    ) {
        switch ($suffix) {
            case FileGenerator::TYPE_SEEDER_SUFFIX:
                $stub = $this->populateDataTypeSeederContent($stub, $data_type);
                break;
            case FileGenerator::ROW_SEEDER_SUFFIX:
                $stub = $this->populateDataRowSeederContent($stub, $data_type);
                break;
            case FileGenerator::DELETED_SEEDER_SUFFIX:
                $stub = $this->populateCRUDDataDeletedSeederContent($stub, $data_type);
                break;
        }

        return $stub;
    }

    /**
     * Update Deployment Orchestra Seeder Content.
     *
     * @param  string  $class_name
     * @param  string  $content
     * @return mixed|string|string[]|null
     */
    public function updateDeploymentOrchestraSeederContent($class_name, $content)
    {
        return $this->content_generator->generateOrchestraSeederContent($class_name, $content);
    }

    /**
     * Populate Data Type Seeder Content.
     *
     * @return mixed|string
     */
    private function populateDataTypeSeederContent(string $stub, DataType $data_type)
    {
        $table_name = $data_type->getTable();
        $stub = $this->populateDeleteStatements($stub, $data_type);
        $stub = $this->populatePermissionStatements($stub, $data_type);
        $stub = $this->populateMenuStatements($stub, $data_type);

        $data_type_array = $data_type->toArray();

        unset($data_type_array['translations']);
        unset($data_type_array['data_rows']);

        return $this->populateInsertStatements(
            $stub,
            $table_name,
            $data_type_array,
            '{{insert_statements}}'
        );
    }

    /**
     * Populate Data Row Seeder Content.
     *
     * @return mixed|string
     */
    private function populateDataRowSeederContent(string $stub, DataType $data_type)
    {
        $rows = $data_type->dataRows;
        $data_array = $this->repackContentData($rows->toArray());
        $table_name = $rows->last()->getTable();

        $stub = str_replace(
            '{{datatype_slug_statement}}',
            $this->content_generator->getDataTypeSlugStatement($data_type),
            $stub
        );

        return $this->populateInsertStatements(
            $stub,
            $table_name,
            $data_array,
            '{{insert_statements}}'
        );
    }

    /**
     * Populate CRUDData Deleted Seeder Content.
     *
     * @return mixed|string
     */
    private function populateCRUDDataDeletedSeederContent(string $stub, DataType $data_type)
    {
        $stub = $this->populateDeleteStatements($stub, $data_type);

        if ($data_type->generate_permissions) {
            $stub = $this->replaceString(
                '{{permission_delete_statements}}',
                $this->content_generator->getPermissionStatement($data_type, 'delete'),
                $stub
            );
        } else {
            $stub = $this->replaceString(
                '{{permission_delete_statements}}',
                '',
                $stub
            );
        }

        return $this->replaceString(
            '{{menu_delete_statements}}',
            $this->content_generator->generateMenuDeleteStatements($data_type),
            $stub
        );
    }

    /**
     * Populate Permission Statements.
     *
     * @return mixed|string
     */
    private function populatePermissionStatements(string $stub, DataType $data_type)
    {
        if ($data_type->generate_permissions) {
            $stub = $this->replaceString(
                '{{permission_insert_statements}}',
                $this->content_generator->getPermissionStatement($data_type),
                $stub
            );
        } else {
            $stub = $this->replaceString(
                '{{permission_insert_statements}}',
                '',
                $stub
            );
        }

        return $stub;
    }

    /**
     * Populate Delete Statements.
     *
     * @return mixed|string
     */
    private function populateDeleteStatements(string $stub, DataType $data_type)
    {
        return $this->replaceString(
            '{{delete_statements}}',
            $this->content_generator->getDeleteStatement($data_type),
            $stub
        );
    }

    /**
     * Populate Menu Statements.
     *
     * @return mixed|string
     */
    private function populateMenuStatements(string $stub, DataType $data_type)
    {
        return $this->replaceString(
            '{{menu_insert_statements}}',
            $this->content_generator->getMenuInsertStatements($data_type),
            $stub
        );
    }

    /**
     * Populate Insert Statements.
     *
     * @return mixed|string
     */
    private function populateInsertStatements(
        string $stub,
        string $table_name,
        array $data_type_array,
        $insert_statement_string
    ) {
        $inserts = '';
        $inserts .= sprintf(
            "\DB::table('%s')->insert(%s);",
            $table_name,
            $this->content_generator->formatContent($data_type_array)
        );

        return $this->replaceString($insert_statement_string, $inserts, $stub);
    }

    /**
     * Replace String.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  $stub
     * @return mixed
     */
    public function replaceString($search, $replace, $stub)
    {
        return str_replace($search, $replace, $stub);
    }

    public function populateTableContentToSeeder(string $stub, string $table_name, array $data)
    {
        return $this->populateInsertStatements($stub, $table_name, $data, '{{insert_statements}}');
    }
}
