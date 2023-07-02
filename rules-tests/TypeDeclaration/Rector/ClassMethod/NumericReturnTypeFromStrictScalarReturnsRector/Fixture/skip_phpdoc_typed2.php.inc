<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTyped
{
    /**
     * @param int $first
     */
    public function resolve($first, int $second) {
        return $first + $second;
    }
}
