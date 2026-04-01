<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipWithReset
{
    public function run()
    {
        $data = [['a']];
        return array_map(reset(...), $data);
    }
}
