<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipFloatOrInt
{
    public function resolve($condition)
    {
        if ($condition)  {
            return 525.252;
        }

        return 1000;
    }
}
