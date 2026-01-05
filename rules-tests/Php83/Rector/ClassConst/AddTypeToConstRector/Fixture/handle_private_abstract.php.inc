<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

abstract class HandlePrivateAbstract
{
    private const VALUE = 1000;
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

abstract class HandlePrivateAbstract
{
    private const int VALUE = 1000;
}
?>
