<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class FirebaseCloudMessages extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'token_get_message',
    ];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = self::class;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
}
