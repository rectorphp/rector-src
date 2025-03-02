<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Source\SomeAssignedObject;

final class SkipComplexStructures
{
    public function run()
    {
        $complexObjects = [];

        $complexObjects[] = new SomeAssignedObject('Tom', 99);
        $complexObjects[] = new SomeAssignedObject('John', 99);

        return $complexObjects;
    }
}
