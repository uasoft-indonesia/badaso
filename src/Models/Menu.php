<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Uasoft\Badaso\Traits\Uuid;

class Menu extends Model
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
        $this->table = $prefix.'menus';
        parent::__construct($attributes);
    }

    protected $guarded = [];

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Menu';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
