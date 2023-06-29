<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class MulFloatInteger
{
    public function resolve(int $first, float $second)
    {
        return $first * $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class MulFloatInteger
{
    public function resolve(int $first, float $second): float
    {
        return $first * $second;
    }
}

?>
