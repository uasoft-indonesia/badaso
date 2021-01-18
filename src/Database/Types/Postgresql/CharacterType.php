<?php

namespace Uasoft\Badaso\Database\Types\Postgresql;

use Uasoft\Badaso\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
