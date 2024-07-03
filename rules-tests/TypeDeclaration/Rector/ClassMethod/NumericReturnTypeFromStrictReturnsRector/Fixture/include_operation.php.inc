<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictReturnsRector\Fixture;

final class IncludeOperation
{
    public function resolve(int $value)
    {
        return ++$value;
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictReturnsRector\Fixture;

final class IncludeOperation
{
    public function resolve(int $value): int
    {
        return ++$value;
    }
}
