<?php

namespace Uasoft\Badaso\Database\Platforms;

use Illuminate\Support\Collection;

abstract class Platform
{
    // abstract public static function getTypes(Collection $type_mapping);

    // abstract public static function registerCustomTypeOptions();

    public static function getPlatform($platform_name)
    {
        $platform = __NAMESPACE__.'\\'.ucfirst($platform_name);

        if (! class_exists($platform)) {
            throw new \Exception("Platform {$platform_name} doesn't exist");
        }

        return $platform;
    }

    public static function getPlatformTypes($platform_name, Collection $type_mapping)
    {
        $platform = static::getPlatform($platform_name);

        return $platform::getTypes($type_mapping);
    }

    public static function registerPlatformCustomTypeOptions($platform_name)
    {
        $platform = static::getPlatform($platform_name);

        return $platform::registerCustomTypeOptions();
    }
}
