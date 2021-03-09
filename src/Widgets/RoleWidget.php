<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Models\Role;

class RoleWidget implements WidgetInterface
{
    public function run($params = null)
    {
        return [
            'label' => 'Role',
            'value' => Role::count(),
        ];
    }
}
