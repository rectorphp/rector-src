<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class SkipDifferentExpr2
{
    public function run($obj, $other)
    {
        return $other instanceof \DateTime && is_object($obj);
    }
}
