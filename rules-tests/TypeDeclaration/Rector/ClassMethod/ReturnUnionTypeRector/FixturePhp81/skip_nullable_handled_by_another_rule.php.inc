<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class SkipNullableHandledByAnotherRule
{
    public function run($value)
    {
        if ($value) {
            return null;
        }

        return false;
    }
}
