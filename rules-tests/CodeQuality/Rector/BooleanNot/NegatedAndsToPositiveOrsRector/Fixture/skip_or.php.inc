<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class SkipOr
{
    public function run($a, $b)
    {
        $result = !($a > 20 || $b <= 50);
    }
}
