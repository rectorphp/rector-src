<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class SkipDifferentExpr3
{
    public function run($obj)
    {
        return is_object($obj) && count($obj->data) === 1;
    }
}
