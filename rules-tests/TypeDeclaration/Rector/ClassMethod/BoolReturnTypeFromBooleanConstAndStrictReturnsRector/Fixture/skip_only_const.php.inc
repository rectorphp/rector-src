<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class SkipOnlyConst
{
    public function resolve($value)
    {
        if ($value) {
            return false;
        }

        return true;
    }
}
