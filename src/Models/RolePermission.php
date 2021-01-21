<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RolePermission extends Model
{
    use LogsActivity;

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'RolePermission';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
