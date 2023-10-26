<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipIntFloat
{
    public function resolve($param, float $first, float $second)
    {
        if (! $param) {
            return $first - $second;
        }

        return 1 + (int) $param;
    }
}
