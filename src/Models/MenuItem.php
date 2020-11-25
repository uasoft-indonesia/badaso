<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
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
}
