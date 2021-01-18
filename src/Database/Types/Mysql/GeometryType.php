<?php

namespace Uasoft\Badaso\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class GeometryType extends Type
{
    const NAME = 'geometry';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'geometry';
    }
}
