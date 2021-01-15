<?php

namespace Uasoft\Badaso\Listeners;

use Uasoft\Badaso\BadasoDeploymentOrchestrator;
use Uasoft\Badaso\Events\CRUDDataChanged;

class BadasoCRUDDataChanged
{
    /** @var BadasoDeploymentOrchestrator */
    private $deployment_orchestrator;

    /**
     * BadasoCRUDDataChanged constructor.
     */
    public function __construct(BadasoDeploymentOrchestrator $orchestrator)
    {
        $this->deployment_orchestrator = $orchestrator;
    }

    public function handle(CRUDDataChanged $crudDataChanged)
    {
        return $this->deployment_orchestrator->handle($crudDataChanged);
    }
}
