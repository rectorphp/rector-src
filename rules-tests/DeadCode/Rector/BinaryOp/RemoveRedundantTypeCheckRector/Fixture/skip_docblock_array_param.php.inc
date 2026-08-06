<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class SkipDocblockArrayParam
{
    /**
     * @param mixed[] $clauses
     */
    public function run($clauses): void
    {
        if ($clauses && is_array($clauses)) {
            return;
        }
    }
}
