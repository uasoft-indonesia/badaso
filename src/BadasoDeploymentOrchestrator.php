<?php

namespace Uasoft\Badaso;

use Exception;
use Illuminate\Foundation\Application;
use Uasoft\Badaso\Events\BreadChanged;
use Uasoft\Badaso\Exceptions\OrchestratorHandlerNotFoundException;
use Uasoft\Badaso\OrchestratorHandlers\BreadAddedHandler;
use Uasoft\Badaso\OrchestratorHandlers\BreadDeletedHandler;
use Uasoft\Badaso\OrchestratorHandlers\BreadUpdatedHandler;

class BadasoDeploymentOrchestrator
{
    /** @var string */
    const BREAD_ADDED = 'Added';

    /** @var string */
    const BREAD_UPDATED = 'Updated';

    /** @var string */
    const BREAD_DELETED = 'Deleted';

    /** @var array */
    const HANDLERS = [
        self::BREAD_ADDED => BreadAddedHandler::class,
        self::BREAD_UPDATED => BreadUpdatedHandler::class,
        self::BREAD_DELETED => BreadDeletedHandler::class,
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
     * BreadChanged Handlers.
     *
     * @throws DeploymentHandlerNotFoundException
     */
    public function handle(BreadChanged $bread_changed)
    {
        \Log::debug($bread_changed->data_type->name);
        if (!in_array(
            $bread_changed->data_type->name,
            config('badaso.watch_tables')
        )
        ) {
            return;
        }

        try {
            \Log::debug('Handle');
            $handler = $this->getHandle($bread_changed->change_type);

            if ($handler) {
                $handler->handle($bread_changed);
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
        \Log::debug($change_type);
        if (isset(self::HANDLERS[$change_type])) {
            return $this->app->make(self::HANDLERS[$change_type]);
        }
    }
}
