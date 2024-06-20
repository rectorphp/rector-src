<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypeWhenNoChildren
{
    private const A = 1000;
    public const B = 'foo';
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypeWhenNoChildren
{
    private const int A = 1000;
    public const string B = 'foo';
}
?>
