<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IncraseNumber
{
    public function resolve(int $first)
    {
        return ++$first;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IncraseNumber
{
    public function resolve(int $first): int
    {
        return ++$first;
    }
}

?>
