<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Source\FooBar;

final class SkipZeroArityOptionalParamOnCallee
{
    public function run(?\Closure $callback = null)
    {
        return $this->apply($callback ?? fn () => FooBar::optionalArgs());
    }

    private function apply(callable $callback)
    {
        return $callback('not optional-args input');
    }
}
