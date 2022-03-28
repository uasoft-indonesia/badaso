<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserRole extends Model
{
    use LogsActivity;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'user_roles';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'UserRole';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->dontSubmitEmptyLogs();
    }
}
