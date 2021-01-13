<?php

namespace Uasoft\Badaso\OrchestratorHandlers;

use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Events\BreadChanged;

class BreadAddedHandler
{
    /** @var FileGenerator */
    private $file_generator;

    /**
     * BreadAddedHandler constructor.
     *
     * @param FilesGenerator $file_generator
     */
    public function __construct(FileGenerator $file_generator)
    {
        \Log::debug(get_class($this));
        $this->file_generator = $file_generator;
    }

    /**
     * Bread Added Handler.
     *
     * @return bool
     */
    public function handle(BreadChanged $bread_added)
    {
        $data_type = $bread_added->data_type;

        // Generate Data Type Seeder File.
        $this->file_generator->generateDataTypeSeedFile($data_type);

        // Generate Data Row Seeder File.
        return $this->file_generator->generateDataRowSeedFile($data_type);
    }
}
