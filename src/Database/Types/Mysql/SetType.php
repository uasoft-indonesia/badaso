<?php

namespace Uasoft\Badaso\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Database\Types\Type;

class SetType extends Type
{
    const NAME = 'set';

    public function getSQLDeclaration(array $field_declaration, AbstractPlatform $platform)
    {
        throw new \Exception('Set type is not supported');
        // we're going to store SET values in the comment since DBAL doesn't support
        $allowed = explode(',', trim($field_declaration['comment']));

        $pdo = DB::connection()->getPdo();

        // trim the values
        $field_declaration['allowed'] = array_map(function ($value) use ($pdo) {
            return $pdo->quote(trim($value));
        }, $allowed);

        return 'set('.implode(', ', $field['allowed']).')';
    }
}
