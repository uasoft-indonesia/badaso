<?php

namespace Uasoft\Badaso\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class MediumTextType extends Type
{
    const NAME = 'mediumtext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'mediumtext';
    }
}
