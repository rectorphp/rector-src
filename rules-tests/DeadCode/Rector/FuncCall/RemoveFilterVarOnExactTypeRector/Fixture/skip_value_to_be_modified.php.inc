<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class SkipValueToBeModified
{
    public function run()
    {
        return filter_var('0755', FILTER_VALIDATE_INT);
    }
}
