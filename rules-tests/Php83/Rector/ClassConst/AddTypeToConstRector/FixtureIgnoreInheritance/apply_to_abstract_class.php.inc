<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

abstract class ApplyToAbstractClass
{
    public const STRING = 'some_type';
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

abstract class ApplyToAbstractClass
{
    public const string STRING = 'some_type';
}

?>
