<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class SkipNestedAnd
{
    public function run($a, $b, $c)
    {
        $result = !($a > 20 && $b < $c && $a !== null);
    }
}
