<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;

class DataRow extends Model
{
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
}
