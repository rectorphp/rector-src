<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class RemoveFilterVarOnConstantFetch
{
    public function run(int $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
