<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SkipPossibleVoid
{
    public function run()
    {
        $stdClass = new stdClass;

        if (rand(0, 1)) {
            $stdClass->foo = 1;
            return $stdClass;
        }

        if (rand(0, 1)) {
            $stdClass->bar = 1;
            return $stdClass;
        }
    }
}
