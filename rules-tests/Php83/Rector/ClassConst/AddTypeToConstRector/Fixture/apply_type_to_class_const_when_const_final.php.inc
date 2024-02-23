<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypesWhenConstFinal
{
    final public const STRING = 'some_type';

    final public const INT = 1;

    final public const FLOAT = 1.0;

    final public const BOOL = true;

    final public const NULL = null;

    final public const ARRAY = [];

    final public const CONCAT = self::STRING . 'concat';
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypesWhenConstFinal
{
    final public const string STRING = 'some_type';

    final public const int INT = 1;

    final public const float FLOAT = 1.0;

    final public const bool BOOL = true;

    final public const null NULL = null;

    final public const array ARRAY = [];

    final public const string CONCAT = self::STRING . 'concat';
}

?>
