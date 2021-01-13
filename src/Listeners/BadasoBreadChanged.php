<?php

namespace Uasoft\Badaso\Listeners;

use Uasoft\Badaso\BadasoDeploymentOrchestrator;
use Uasoft\Badaso\Events\BreadChanged;

class BadasoBreadChanged
{
    /** @var BadasoDeploymentOrchestrator */
    private $deployment_orchestrator;

    /**
     * BadasoBreadChanged constructor.
     */
    public function __construct(BadasoDeploymentOrchestrator $orchestrator)
    {
        \Log::debug(get_class($this));
        $this->deployment_orchestrator = $orchestrator;
    }

    public function handle(BreadChanged $breadChanged)
    {
        return $this->deployment_orchestrator->handle($breadChanged);
    }
}
