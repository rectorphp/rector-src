<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class BinaryShiftAgain
{
    public function resolve(int $first, int $second)
    {
        return $first << $second;
    }

    public function resolveRight(int $first, int $second)
    {
        return $first >> $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class BinaryShiftAgain
{
    public function resolve(int $first, int $second): int
    {
        return $first << $second;
    }

    public function resolveRight(int $first, int $second): int
    {
        return $first >> $second;
    }
}

?>
