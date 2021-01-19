<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DataRow extends Model
{
    use LogsActivity;

    protected $table = 'data_rows';

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

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This table has been {$eventName}";
    }
}
