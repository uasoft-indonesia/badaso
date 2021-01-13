<?php

namespace Uasoft\Badaso\Events;

use Illuminate\Queue\SerializesModels;
use Uasoft\Badaso\Models\DataType;

class BreadChanged
{
    use SerializesModels;

    public $data_type;

    public $data;

    public $change_type;

    public function __construct(DataType $data_type, $data, $change_type)
    {
        \Log::debug(get_class($this));

        $this->data_type = $data_type;

        $this->data = $data;

        $this->change_type = $change_type;
    }
}
