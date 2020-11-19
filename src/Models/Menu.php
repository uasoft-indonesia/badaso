<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'key',
        'display_name',
    ];
}
