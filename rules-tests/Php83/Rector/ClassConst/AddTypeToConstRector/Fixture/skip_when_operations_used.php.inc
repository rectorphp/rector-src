<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class SomeClass
{
    public const string A = 'A';

    public const B = self::A . 'b';

    public const int INT = 1;

    public const INT2 = 1 + self::INT;
}

?>
