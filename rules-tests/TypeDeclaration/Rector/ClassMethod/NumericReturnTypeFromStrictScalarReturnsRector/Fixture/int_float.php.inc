<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IntFloat
{
    public function resolve($param, float $first, float $second)
    {
        if (! $param) {
            return $first - $second;
        }

        return 1 + (int) $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IntFloat
{
    public function resolve($param, float $first, float $second): float|int
    {
        if (! $param) {
            return $first - $second;
        }

        return 1 + (int) $param;
    }
}

?>
