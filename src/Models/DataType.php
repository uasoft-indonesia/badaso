<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Uasoft\Badaso\Facades\Badaso;

class DataType extends Model
{
    protected $table = 'data_types';

    public function dataRows()
    {
        return $this->hasMany(Badaso::model('DataRow'));
    }
}
