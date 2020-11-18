<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'key',
        'display_name',
        'value',
        'details',
        'type',
        'order',
        'group',
    ];
}
