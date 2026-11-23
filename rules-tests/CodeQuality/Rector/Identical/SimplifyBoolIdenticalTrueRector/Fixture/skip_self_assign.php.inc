<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SkipSelfAssign
{
    public function run(bool $value, array $items)
    {
        $match = in_array($value, $items, true);
        $match = false !== $match;
        $match = $match === true;
    }
}

?>
