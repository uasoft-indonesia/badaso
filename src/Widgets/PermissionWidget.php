<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Models\Permission;

class PermissionWidget implements WidgetInterface
{
    public function run($params = null)
    {
        return [
            'label' => 'Permission',
            'value' => Permission::count(),
        ];
    }
}
