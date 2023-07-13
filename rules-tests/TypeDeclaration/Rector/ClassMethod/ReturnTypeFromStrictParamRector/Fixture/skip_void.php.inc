<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipPossibleVoid
{
    public function run(int $p)
    {
        if (rand(0, 1)) {
            return $p;
        }
    }
}
