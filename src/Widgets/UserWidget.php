<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Models\User;

class UserWidget implements WidgetInterface
{
    public function run($params = null)
    {
        return [
            'label' => 'User',
            'value' => User::count(),
        ];
    }
}
