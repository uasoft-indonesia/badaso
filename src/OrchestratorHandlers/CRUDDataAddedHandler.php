<?php

namespace Uasoft\Badaso\OrchestratorHandlers;

use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Events\CRUDDataChanged;

class CRUDDataAddedHandler
{
    /** @var FileGenerator */
    private $file_generator;

    /**
     * CRUDDataAddedHandler constructor.
     *
     * @param  FilesGenerator  $file_generator
     */
    public function __construct(FileGenerator $file_generator)
    {
        $this->file_generator = $file_generator;
    }

    /**
     * CRUDData Added Handler.
     *
     * @return bool
     */
    public function handle(CRUDDataChanged $crud_data_added)
    {
        $data_type = $crud_data_added->data_type;

        // Generate Data Type Seeder File.
        $this->file_generator->generateDataTypeSeedFile($data_type);

        // Generate Data Row Seeder File.
        return $this->file_generator->generateDataRowSeedFile($data_type);
    }
}
