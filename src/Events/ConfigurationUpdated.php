<?php

namespace Uasoft\Badaso\Events;

use Illuminate\Queue\SerializesModels;
use Uasoft\Badaso\Models\Configuration;

class ConfigurationUpdated
{
    use SerializesModels;

    public $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
}
