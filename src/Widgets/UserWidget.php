<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Models\User;

class UserWidget implements WidgetInterface
{
    /**
     * Set permission for widget
     * set null to allow all role
     * multiple permission allowed, separate by comma.
     */
    public function getPermissions()
    {
        return 'browse_users';
    }

    public function run($params = null)
    {
        return [
            'label' => 'User',
            'icon' => 'person',
            'value' => User::count(),
            'prefix_value' => '',
            'delimiter' => '.',
        ];
    }
}
