<?php

namespace Uasoft\Badaso\Events;

use Illuminate\Queue\SerializesModels;
use Uasoft\Badaso\Models\DataType;

class BreadUpdated
{
    use SerializesModels;

    public $data_type;

    public $data;

    public function __construct(DataType $data_type, $data)
    {
        $this->data_type = $data_type;

        $this->data = $data;

        event(new BreadChanged($data_type, $data, 'Updated'));
    }
}
