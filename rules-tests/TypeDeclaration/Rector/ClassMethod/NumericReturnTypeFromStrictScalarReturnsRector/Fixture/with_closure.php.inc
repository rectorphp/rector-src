<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class WithClosure
{
    public function resolve(int $first, int $second)
    {
        return function () use ($first, $second) {
            return $first + $second;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class WithClosure
{
    public function resolve(int $first, int $second)
    {
        return function () use ($first, $second): int {
            return $first + $second;
        };
    }
}

?>
