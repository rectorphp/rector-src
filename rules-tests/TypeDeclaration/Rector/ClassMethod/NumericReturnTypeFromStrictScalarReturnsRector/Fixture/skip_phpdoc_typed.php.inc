<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTyped
{
    /** * @param 0|1 $first */
    public function resolve($first, int $second) {
        return $first + $second;
    }
}
