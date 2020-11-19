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
}
