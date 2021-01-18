<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class ByteaType extends Type
{
    const NAME = 'bytea';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'bytea';
    }
}
