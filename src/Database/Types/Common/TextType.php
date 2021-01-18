<?php

namespace Uasoft\Badaso\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class TextType extends Type
{
    const NAME = 'text';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'text';
    }
}
