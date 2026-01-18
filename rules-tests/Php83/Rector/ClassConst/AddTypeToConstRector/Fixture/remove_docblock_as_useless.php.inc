<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class RemoveDocBlockAsUseless
{
    /**
     * @var int
     */
    private const A = 1000;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class RemoveDocBlockAsUseless
{
    private const int A = 1000;
}

?>
