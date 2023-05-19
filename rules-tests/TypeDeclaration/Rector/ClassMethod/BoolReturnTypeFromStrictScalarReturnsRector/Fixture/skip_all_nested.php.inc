<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipAllNested
{
    public function resolve($first, $second)
    {
        if ($first) {
            return false;
        }

        if ($second) {
            return $first > $second;
        }
    }
}
