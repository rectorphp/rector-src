<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class SkipUnionIntersection
{
    public function run(\Iterator&\Countable $obj)
    {
        if (rand(0, 1)) {
            return $obj;
        }

        return '10';
    }
}
