<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyToPublicConst
{
    private const A = 1000;
    public const B = 'foo';
    public const C = 'bar';
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyToPublicConst
{
    private const int A = 1000;
    public const string B = 'foo';
    public const string C = 'bar';
}
?>
