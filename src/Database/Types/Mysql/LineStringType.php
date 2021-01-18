<?php

namespace Uasoft\Badaso\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class LineStringType extends Type
{
    const NAME = 'linestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'linestring';
    }
}
