<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipSingleNested
{
    public function resolve($first, $second)
    {
        if ($first) {
            return false;
        }
    }
}
