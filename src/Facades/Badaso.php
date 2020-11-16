<?php

namespace Uasoft\Badaso\Facades;

use Illuminate\Support\Facades\Facade;

class Badaso extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'badaso';
    }
}
