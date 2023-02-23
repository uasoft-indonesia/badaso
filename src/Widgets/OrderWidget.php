<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Module\MLM\Models\Wallet;

class OrderWidget implements WidgetInterface
{
    /**
     * Set permission for widget
     * set null to allow all role
     * multiple permission allowed, separate by comma.
     */
    public function getPermissions()
    {
        return 'browse_orders';
    }

    public function run($params = null)
    {
        $wallet = Wallet::get();
        $data = $wallet->sum('balance');
        return [
            'label' => 'Wallet',
            'icon' => 'shopping_basket',
            'value' => $data,
            'prefix_value' => '',
            'delimiter' => ',',
        ];
    }
}
