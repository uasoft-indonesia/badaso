<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class SmallIntType extends Type
{
    const NAME = 'smallint';
    const DBTYPE = 'int2';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $common_integer_type_declaration = call_protected_method($platform, '_getCommonIntegerTypeDeclarationSQL', $field);

        $type = $field['autoincrement'] ? 'smallserial' : 'smallint';

        return $type.$common_integer_type_declaration;
    }
}
