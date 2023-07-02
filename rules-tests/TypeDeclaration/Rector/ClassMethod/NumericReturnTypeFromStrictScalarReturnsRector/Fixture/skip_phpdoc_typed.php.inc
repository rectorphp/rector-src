<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTyped
{
    /**
     * @param 0|1 $first
     */
    public function resolveLeft($first, int $second) {
        return $first + $second;
    }

    /**
     * @param 0|1 $second
     */
    public function resolveRight(int $first, $second) {
        return $first + $second;
    }
}
