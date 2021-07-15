<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Uasoft\Badaso\Traits\Uuid;

class Role extends Model
{
    use LogsActivity, Uuid;

    protected $table = null;

    public $incrementing = false;

    public $keyType = 'string';

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'roles';
        parent::__construct($attributes);
    }

    protected $guarded = [];

    public function users()
    {
        $userModel = User::class;

        return $this->belongsToMany($userModel, config('badaso.database.prefix').'user_roles')
            ->select(app($userModel)->getTable().'.*')
            ->union($this->hasMany($userModel))->getQuery();
    }

    public function user_roles()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, config('badaso.database.prefix').'role_permissions');
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Role';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
