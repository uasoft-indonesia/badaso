<?php

namespace Uasoft\Badaso\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Uasoft\Badaso\Database\Types\Type;

class TinyIntType extends Type
{
    const NAME = 'tinyint';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $common_integer_type_declaration = call_protected_method($platform, '_getCommonIntegerTypeDeclarationSQL', $field);

        return 'tinyint'.$common_integer_type_declaration;
    }
}
