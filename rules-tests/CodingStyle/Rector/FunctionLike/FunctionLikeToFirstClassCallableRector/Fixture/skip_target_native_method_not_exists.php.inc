<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

class SkipTargetNativeMethodNotExists
{
    public function callTest()
    {
        $test = array_map(fn($value) => SkipTargetNativeMethodNotExists::test($value), [1, 2, 3, 4]);
    }
}
