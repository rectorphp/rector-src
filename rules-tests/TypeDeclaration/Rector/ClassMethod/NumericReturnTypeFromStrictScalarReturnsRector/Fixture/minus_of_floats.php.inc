<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class MinusOfFloats
{
    public function resolve(float $first, float $second)
    {
        return $first - $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class MinusOfFloats
{
    public function resolve(float $first, float $second): float
    {
        return $first - $second;
    }
}

?>
