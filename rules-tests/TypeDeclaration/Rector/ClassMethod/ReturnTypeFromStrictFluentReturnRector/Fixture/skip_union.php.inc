<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class SkipUnion
{
    public function test()
    {
        if (rand(0, 1)) {
            return 1;
        }

        return $this;
    }
}