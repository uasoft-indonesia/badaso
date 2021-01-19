<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MenuItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'menu_id',
        'title',
        'url',
        'target',
        'icon_class',
        'color',
        'parent_id',
        'order',
    ];

    public function highestOrderMenuItem($parent = null)
    {
        $order = 1;

        $item = $this->where('parent_id', '=', $parent)
            ->where('menu_id', $this->menu_id)
            ->orderBy('order', 'DESC')
            ->first();

        if (!is_null($item)) {
            $order = intval($item->order) + 1;
        }

        return $order;
    }

    public function hasChildren()
    {
        $count = $this->where('parent_id', '=', $this->id)
            ->count();

        return $count > 0;
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Menu Item';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
