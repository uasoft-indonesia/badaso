<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Configuration extends Model
{
    use LogsActivity;

    protected $fillable = [
        'key',
        'display_name',
        'value',
        'details',
        'type',
        'order',
        'group',
        'can_delete',
    ];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Configuration';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function getCanDeleteAttribute($value)
    {
        return $value == 1;
    }
}
