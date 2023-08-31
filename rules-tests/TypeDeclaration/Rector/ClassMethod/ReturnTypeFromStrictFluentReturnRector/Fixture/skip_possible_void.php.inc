<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class SkipPossibleVoid
{
    public function test()
    {
        if (rand(0, 1)) {
            return;
        }

        return $this;
    }
}