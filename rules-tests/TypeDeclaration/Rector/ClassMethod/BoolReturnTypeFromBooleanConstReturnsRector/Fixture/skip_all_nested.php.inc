<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\Fixture;

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
