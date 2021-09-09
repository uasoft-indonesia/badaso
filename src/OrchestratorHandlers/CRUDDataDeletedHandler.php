<?php

namespace Uasoft\Badaso\OrchestratorHandlers;

use Uasoft\Badaso\ContentManager\FileGenerator;
use Uasoft\Badaso\Events\CRUDDataChanged;
use Uasoft\Badaso\Models\Permission;

class CRUDDataDeletedHandler
{
    /** @var FileGenerator */
    private $file_generator;

    /**
     * BadasoDeleted constructor.
     *
     * @param  FilesGenerator  $file_generator
     */
    public function __construct(FileGenerator $file_generator)
    {
        $this->file_generator = $file_generator;
    }

    /**
     * CRUDData Deleted Handler.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(CRUDDataChanged $crud_data_changed): bool
    {
        $data_type = $crud_data_changed->data_type;

        $data_type->destroy($data_type->id);

        if (! is_null($data_type)) {
            Permission::removeFrom($data_type->name);
        }

        // Finally, We can delete seed files.
        $this->file_generator->deleteSeedFiles($data_type);

        // After deleting seeders file, we create new seed file in order to rollback
        // the seeded data.
        return $this->file_generator->generateSeedFileForDeletedData($data_type);
    }
}
