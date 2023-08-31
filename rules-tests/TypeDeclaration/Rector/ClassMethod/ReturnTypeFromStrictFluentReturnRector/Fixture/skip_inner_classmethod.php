<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

final class SkipInnerClassMethod
{
    public function test()
    {
        new class {
            public function run()
            {
                return $this;
            }
        };
    }
}
