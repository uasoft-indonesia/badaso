<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FirebaseServices extends Model
{
    use LogsActivity;

    protected $fillable = [
        'apiKey',
        'authDomain',
        'projectId',
        'storageBucket',
        'messagingSenderId',
        'appId',
        'measureId',
        'serverKey',
    ];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Configuration';
}
