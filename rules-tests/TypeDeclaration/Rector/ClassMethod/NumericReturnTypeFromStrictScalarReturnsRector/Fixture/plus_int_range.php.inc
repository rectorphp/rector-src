<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusIntRange
{
    public function resolve(int $first, int $second)
    {
        if ($second > 0) {
            $second = 100;
        }
        return $first + $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusIntRange
{
    public function resolve(int $first, int $second): int
    {
        if ($second > 0) {
            $second = 100;
        }
        return $first + $second;
    }
}

?>
