<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipUnionFalse
{
    public function resolve($param)
    {
        if (! $param) {
            return false;
        }

        return 1 + (int) $param;
    }
}
