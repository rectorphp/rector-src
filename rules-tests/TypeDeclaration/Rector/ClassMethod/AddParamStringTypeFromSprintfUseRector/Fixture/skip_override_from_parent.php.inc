<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Source\SomeParentClass;

final class SkipOverrideFromParent extends SomeParentClass
{
    public function test($name)
    {
        return sprintf('Hello %s', $name);
    }
}
