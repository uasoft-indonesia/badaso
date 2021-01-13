<?php

namespace Uasoft\Badaso\OrchestratorHandlers;

use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Events\BreadChanged;

class BreadUpdatedHandler
{
    /** @var FileGenerator */
    private $file_generator;

    /**
     * BreadUpdatedHandler constructor.
     *
     * @param FilesGenerator $file_generator
     */
    public function __construct(FileGenerator $file_generator)
    {
        \Log::debug(get_class($this));
        $this->file_generator = $file_generator;
    }

    /**
     * Bread Updated Handler.
     */
    public function handle(BreadChanged $bread_changed)
    {
        $data_type = $bread_changed->data_type;

        return $this->file_generator->deleteAndGenerate($data_type);
    }
}
