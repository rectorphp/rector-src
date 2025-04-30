<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class SkipFromDocblock
{
    /**
     * @param int $value
     */
    public function run($value): false|int
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}
