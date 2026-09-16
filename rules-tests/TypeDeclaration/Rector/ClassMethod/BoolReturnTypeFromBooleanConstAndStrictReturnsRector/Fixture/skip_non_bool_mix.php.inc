<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class SkipNonBoolMix
{
    public function resolve($value)
    {
        if ($value) {
            return true;
        }

        return $value;
    }
}
