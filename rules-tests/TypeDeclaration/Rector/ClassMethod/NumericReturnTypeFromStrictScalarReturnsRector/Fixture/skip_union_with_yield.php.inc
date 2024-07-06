<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipUnionWithYield
{
    public function resolve()
    {
        if (rand(0,1)) {
            return 5;
        }

        yield "1";
    }
}
