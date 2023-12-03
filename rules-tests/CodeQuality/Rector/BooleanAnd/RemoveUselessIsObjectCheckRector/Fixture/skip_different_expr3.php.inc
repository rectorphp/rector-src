<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\Fixture;

final class SkipDifferentExpr3
{
    public function run($obj)
    {
        return is_object($obj) && count($obj->data) === 1;
    }
}
