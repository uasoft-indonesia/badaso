<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Uasoft\Badaso\Facades\Badaso;
use Spatie\Activitylog\Traits\LogsActivity;

class DataType extends Model
{
    use LogsActivity;

    protected $table = 'data_types';

    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions',
        'server_side',
        'order_column',
        'order_display_column',
        'order_direction',
        'default_search_key',
        'scope',
        'details',
    ];

    public function dataRows()
    {
        return $this->hasMany(Badaso::model('DataRow'))->orderBy('order', 'asc');
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
