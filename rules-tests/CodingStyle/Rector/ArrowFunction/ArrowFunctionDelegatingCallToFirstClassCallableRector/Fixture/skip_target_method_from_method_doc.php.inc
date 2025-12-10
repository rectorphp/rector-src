<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

/**
 * @method static array test(mixed $value)
 */
class SkipTargetMethodFromMethodDoc
{
    public function callTest()
    {
        $test = array_map(fn($value) => SkipTargetNativeMethodNotExists::test($value), [1, 2, 3, 4]);
    }
}
