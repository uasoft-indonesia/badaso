<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FirebaseServices extends Model
{
    use LogsActivity;

    protected $fillable = [
        'api_key',
        'auth_domain',
        'project_id',
        'storage_bucket',
        'messaging_sender_id',
        'app_id',
        'measurement_id',
        'server_key',
    ];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Configuration';
}
