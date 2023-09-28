<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    // use HasFactory;
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'users';
        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'avatar',
        'address',
        'gender',
        'additional_info',
        'last_sent_token_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_sent_token_at' => 'datetime',
    ];

    protected static $logAttributes = [
        'name',
        'email',
        'avatar',
        'additional_info',
    ];

    protected static $logFillable = [
        'name',
        'email',
        'avatar',
        'additional_info',
    ];

    protected static $logName = 'User';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, config('badaso.database.prefix').'user_roles');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->dontSubmitEmptyLogs();
    }
}
