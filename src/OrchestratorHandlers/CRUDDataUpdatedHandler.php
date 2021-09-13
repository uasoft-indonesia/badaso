<?php

namespace Uasoft\Badaso\OrchestratorHandlers;

use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Events\CRUDDataChanged;

class CRUDDataUpdatedHandler
{
    /** @var FileGenerator */
    private $file_generator;

    /**
     * CRUDDataUpdatedHandler constructor.
     *
     * @param  FilesGenerator  $file_generator
     */
    public function __construct(FileGenerator $file_generator)
    {
        $this->file_generator = $file_generator;
    }

    /**
     * CRUDData Updated Handler.
     */
    public function handle(CRUDDataChanged $crud_data_changed)
    {
        $data_type = $crud_data_changed->data_type;

        return $this->file_generator->deleteAndGenerate($data_type);
    }
}
