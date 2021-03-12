<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailReset extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'email',
        'verification_token',
        'expired_at',
        'count_incorrect',
    ];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'EmailReset';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
