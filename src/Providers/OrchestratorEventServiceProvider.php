<?php

namespace Uasoft\Badaso\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Uasoft\Badaso\Events\CRUDDataChanged;
use Uasoft\Badaso\Listeners\BadasoCRUDDataChanged;

class OrchestratorEventServiceProvider extends EventServiceProvider
{
    /** @var array */
    protected $listen = [
        CRUDDataChanged::class => [
            BadasoCRUDDataChanged::class,
        ],
    ];
}
