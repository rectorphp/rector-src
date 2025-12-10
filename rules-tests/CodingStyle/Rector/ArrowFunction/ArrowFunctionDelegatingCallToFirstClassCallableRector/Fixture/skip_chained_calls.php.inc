<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipChainedCalls
{
    public function run()
    {
        fn ($foo) => FooBar::foo()->bar($foo);
        fn ($foo) => FooBar::foo()::bar($foo);
    }
}
