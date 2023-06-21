<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusInt
{
    public function resolve(int $first, int $second)
    {
        return $first + $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusInt
{
    public function resolve(int $first, int $second): int
    {
        return $first + $second;
    }
}

?>
