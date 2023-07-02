<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusIntNativeTypePhpdocNarrowed
{
    /**
     * @param 0|1 $first
     */
    public function resolve(int $first, int $second)
    {
        return $first + $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

class PlusIntNativeTypePhpdocNarrowed
{
    /**
     * @param 0|1 $first
     */
    public function resolve(int $first, int $second): int
    {
        return $first + $second;
    }
}

?>
