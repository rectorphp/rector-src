<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipVariousReturn
{
    public function resolve($first, $second)
    {
        if ($first) {
            return 100 * $second;
        }

        return $first == $second;
    }
}
