<?php

namespace Uasoft\Badaso\Helpers;

use Uasoft\Badaso\Models\Configuration;

class Config
{
    public static function get($key)
    {
        $config = Configuration::where('key', $key)->first();
        if ($config) {
            return $config->value;
        } else {
            return null;
        }
    }
}
