<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipDependencyOnArg
{
    public function run()
    {
        fn ($foo) => $foo->foo();
    }
}
