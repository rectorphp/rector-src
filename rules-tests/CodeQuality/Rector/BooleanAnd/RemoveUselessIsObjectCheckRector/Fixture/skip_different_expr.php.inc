<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\Fixture;

final class SkipDifferentExpr
{
    public function run($obj, $other)
    {
        return is_object($obj) && $other instanceof \DateTime;
    }
}
