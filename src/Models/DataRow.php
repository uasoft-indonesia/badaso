<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DataRow extends Model
{
    use LogsActivity;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'data_rows';
        parent::__construct($attributes);
    }

    public $timestamps = false;

    public function getRelationAttribute($value)
    {
        $json = $value;

        try {
            $json = json_decode($value, true);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $json;
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Data Row';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->dontSubmitEmptyLogs();
    }
}
