<?php

namespace Uasoft\Badaso\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Uuid
{
    public static function boot()
    {
        parent::boot();
        self::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), (string) Str::uuid());
        });
    }
}
