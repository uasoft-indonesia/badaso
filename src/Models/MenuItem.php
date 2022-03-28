<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MenuItem extends Model
{
    use LogsActivity;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'menu_items';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'menu_id',
        'title',
        'url',
        'target',
        'icon_class',
        'color',
        'parent_id',
        'order',
        'permissions',
    ];

    public function highestOrderMenuItem($menu_id = null, $parent = null)
    {
        $order = 1;

        $menu_id = $menu_id != null ? $menu_id : $this->menu_id;
        $item = self::where('menu_id', $menu_id);

        if ($parent != null) {
            $item = $item->where('parent_id', $parent);
        }

        $item = $item->orderBy('order', 'DESC')
        ->first();

        if (! is_null($item)) {
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->dontSubmitEmptyLogs();
    }
}
