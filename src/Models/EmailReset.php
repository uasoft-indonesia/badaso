<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Uasoft\Badaso\Traits\Uuid;

class EmailReset extends Model
{
    use LogsActivity, Uuid;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'email_resets';
        parent::__construct($attributes);
    }

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
