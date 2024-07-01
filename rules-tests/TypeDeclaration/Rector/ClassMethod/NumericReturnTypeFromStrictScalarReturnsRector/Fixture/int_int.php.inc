<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IntInt
{
    public function resolve($param, int $first, int $second)
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

final class IntInt
{
    public function resolve($param, int $first, int $second): int
    {
        if (! $param) {
            return $first - $second;
        }

        return 1 + (int) $param;
    }
}

?>
