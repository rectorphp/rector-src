<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypesToPrivateConsts
{
    private const STRING = 'some_type';

    private const INT = 1;

    private const FLOAT = 1.0;

    private const BOOL = true;

    private const NULL = null;

    private const ARRAY = [];

    private const CONCAT = self::STRING . 'concat';
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypesToPrivateConsts
{
    private const string STRING = 'some_type';

    private const int INT = 1;

    private const float FLOAT = 1.0;

    private const bool BOOL = true;

    private const null NULL = null;

    private const array ARRAY = [];

    private const string CONCAT = self::STRING . 'concat';
}

?>
