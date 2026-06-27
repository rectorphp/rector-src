<?php

namespace Rector\Tests\Php70\Rector\Assign\ListSwapArrayOrderRector\Fixture;

class SkipAlreadyArrayReverse
{
    public function a()
    {
        list($a[], $a[]) = array_reverse([1, 2]);
    }
}
