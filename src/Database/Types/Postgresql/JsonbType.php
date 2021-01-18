<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class JsonbType extends Type
{
    const NAME = 'jsonb';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'jsonb';
    }
}
