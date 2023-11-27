<?php

namespace Rector\Tests\CodeQuality\Rector\NotEqual\CommonNotEqualRector\Fixture;

final class SkipAlready
{
    public function run($one, $two)
    {
        return $one  !=  $two;
    }
}
