<?php

namespace Uasoft\Badaso\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Uasoft\Badaso\Events\BreadChanged;
use Uasoft\Badaso\Listeners\BadasoBreadChanged;

class OrchestratorEventServiceProvider extends EventServiceProvider
{
    /** @var array */
    protected $listen = [
        BreadChanged::class => [
            BadasoBreadChanged::class,
        ],
    ];
}
