<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

final class SkipNullableHandledByAnotherRule
{
    public function run($value)
    {

        if (rand(0, 1)) {
            return false;
        }

        return null;
    }
}
