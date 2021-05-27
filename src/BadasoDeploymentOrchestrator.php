<?php

namespace Uasoft\Badaso;

use Exception;
use Illuminate\Foundation\Application;
use Uasoft\Badaso\Events\CRUDDataChanged;
use Uasoft\Badaso\Exceptions\OrchestratorHandlerNotFoundException;
use Uasoft\Badaso\OrchestratorHandlers\CRUDDataAddedHandler;
use Uasoft\Badaso\OrchestratorHandlers\CRUDDataDeletedHandler;
use Uasoft\Badaso\OrchestratorHandlers\CRUDDataUpdatedHandler;

class BadasoDeploymentOrchestrator
{
    /** @var string */
    const CRUD_DATA_ADDED = 'Added';

    /** @var string */
    const CRUD_DATA_UPDATED = 'Updated';

    /** @var string */
    const CRUD_DATA_DELETED = 'Deleted';

    /** @var array */
    const HANDLERS = [
        self::CRUD_DATA_ADDED => CRUDDataAddedHandler::class,
        self::CRUD_DATA_UPDATED => CRUDDataUpdatedHandler::class,
        self::CRUD_DATA_DELETED => CRUDDataDeletedHandler::class,
    ];

    /** @var Application */
    private $app;

    /**
     * BadasoDeploymentOrchestrator constructor.
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * CRUDData Handlers.
     *
     * @throws DeploymentHandlerNotFoundException
     */
    public function handle(CRUDDataChanged $crud_data_changed)
    {
        if (! in_array(
            $crud_data_changed->data_type->name,
            config('badaso-watch-tables')
        )
        ) {
            return;
        }

        try {
            $handler = $this->getHandle($crud_data_changed->change_type);

            if ($handler) {
                $handler->handle($crud_data_changed);
            }
        } catch (Exception $e) {
            throw new OrchestratorHandlerNotFoundException($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    private function getHandle(string $change_type)
    {
        if (isset(self::HANDLERS[$change_type])) {
            return $this->app->make(self::HANDLERS[$change_type]);
        }
    }
}
