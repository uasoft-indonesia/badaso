<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Uasoft\Badaso\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
