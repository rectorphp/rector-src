<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\Fixture;

final class SkipDifferentExpr2
{
    public function run($obj, $other)
    {
        return $other instanceof \DateTime && is_object($obj);
    }
}
