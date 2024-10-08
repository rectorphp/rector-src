<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipInMiddleAssign
{
    private int $a = 0;
    
    public function run(int $b)
    {
        $b = $this->a = 0;
        return $b;
    }
}
