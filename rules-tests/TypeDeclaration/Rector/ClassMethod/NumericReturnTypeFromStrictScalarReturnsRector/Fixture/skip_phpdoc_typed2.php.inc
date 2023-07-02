<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTyped
{
    /**
     * @param int $first
     */
    public function resolveLeft($first, int $second) {
        return $first + $second;
    }

    /**
     * @param int $second
     */
    public function resolveRight(int $first, $second) {
        return $first + $second;
    }
}
