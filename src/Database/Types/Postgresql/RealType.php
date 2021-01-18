<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class RealType extends Type
{
    const NAME = 'real';
    const DBTYPE = 'float4';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'real';
    }
}
