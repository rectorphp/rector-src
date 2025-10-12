<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class SkipNonStringDefault
{
    public function test($name = 100)
    {
        return sprintf('Hello %s', $name);
    }
}
