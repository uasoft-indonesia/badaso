<?php

namespace Uasoft\Badaso\Interfaces;

/**
 * @author Sulfano Agus Fikri
 */
interface WidgetInterface
{
    public function getPermissions();

    public function run($params = null);
}
