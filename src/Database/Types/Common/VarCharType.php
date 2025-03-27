<?php

namespace Uasoft\Badaso\Database\Types\Common;

use Doctrine\DBAL\Types\StringType as DoctrineStringType;

class VarCharType extends DoctrineStringType
{
    const NAME = 'string';

    public function getName()
    {
        return 'varchar';
    }
}
