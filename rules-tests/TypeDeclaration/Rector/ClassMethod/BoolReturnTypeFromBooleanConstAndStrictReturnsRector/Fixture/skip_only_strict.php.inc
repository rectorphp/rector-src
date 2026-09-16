<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class SkipOnlyStrict
{
    public function resolve($first, $second)
    {
        if ($first > $second) {
            return $first === $second;
        }

        return $first < $second;
    }
}
