<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Source\MethodCaller;

final class SkipNamedArguments
{
    public function run(MethodCaller $caller)
    {
        $caller->process(first: 1, second: 2);
        $caller->process(1, second: 2);
    }
}
