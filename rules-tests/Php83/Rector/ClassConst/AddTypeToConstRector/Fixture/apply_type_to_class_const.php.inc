<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypes
{
    public const STRING = 'some_type';

    public const INT = 1;

    public const FLOAT = 1.0;

    public const BOOL = true;

    public const NULL = null;

    public const ARRAY = [];
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypes
{
    public const string STRING = 'some_type';

    public const int INT = 1;

    public const float FLOAT = 1.0;

    public const bool BOOL = true;

    public const null NULL = null;

    public const array ARRAY = [];
}

?>
