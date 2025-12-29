<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

class SkipClosureBindTo
{
    public static function close(): void
    {
    }
}

function testClosureBindTo(callable $cb): void
{
    if ($cb instanceof \Closure) {
        $testInstance = new class {
            public function describe() {  }
        };

        $cb = $cb->bindTo($testInstance, get_class($testInstance));
    }

    $cb();
}

testClosureBindTo(fn () => SkipClosureBindTo::close());
