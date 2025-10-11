<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class SkipNonStringMask
{
    public function test($name)
    {
        return sprintf('Hello %d', $name);
    }
}
