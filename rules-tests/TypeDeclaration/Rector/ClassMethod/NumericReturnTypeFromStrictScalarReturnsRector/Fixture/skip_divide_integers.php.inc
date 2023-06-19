<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipDivideIntegers
{
    public function resolve(int $first, int $second)
    {
        return $first / $second;
    }
}
