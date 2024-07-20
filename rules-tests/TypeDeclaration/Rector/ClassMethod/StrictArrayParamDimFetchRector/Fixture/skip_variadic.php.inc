<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class SkipVariadic
{
    public function testDummy(...$args): mixed {
        return $args[count($args) - 1];
    }
}
