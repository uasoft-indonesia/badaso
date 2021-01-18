<?php

namespace Uasoft\Badaso\Database\Types\Common;

use Doctrine\DBAL\Types\FloatType as DoctrineFloatType;

class DoubleType extends DoctrineFloatType
{
    const NAME = 'double';

    public function getName()
    {
        return static::NAME;
    }
}
