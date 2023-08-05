<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class SkipMixedNull
{
    public function run(mixed $param)
    {
        if (rand(0, 1)) {
            return $param;
        }

        return null;
    }
}
