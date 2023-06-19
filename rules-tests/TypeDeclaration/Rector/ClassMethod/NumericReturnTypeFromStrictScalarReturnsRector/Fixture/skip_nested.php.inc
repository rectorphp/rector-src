<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipNested
{
    public function resolve(float $first, float $second)
    {
        if (mt_rand(0, 100)) {
            return $first - $second;
        }

        return null;
    }
}
