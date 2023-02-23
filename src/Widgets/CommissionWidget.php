<?php

namespace Uasoft\Badaso\Widgets;

use Uasoft\Badaso\Interfaces\WidgetInterface;
use Uasoft\Badaso\Module\MLM\Models\Commission;

class CommissionWidget implements WidgetInterface
{
    /**
     * Set permission for widget
     * set null to allow all role
     * multiple permission allowed, separate by comma.
     */
    public function getPermissions()
    {
        return 'browse_commissions';
    }

    public function run($params = null)
    {
        $commission = Commission::get();
        $data= $commission->sum('commission');
        return [
            'label' => 'Commission',
            'icon' => 'account_balance_wallet',
            'value' => $data,
            'prefix_value' =>'Rp',
            'delimiter' =>'.',
        ];

    }
}
