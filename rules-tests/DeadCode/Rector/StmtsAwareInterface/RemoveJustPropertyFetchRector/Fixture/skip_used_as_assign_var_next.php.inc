<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class SkipUsedAsAssignVarNext
{
    public function run($arg)
    {
        $line = $arg->line;
        $total = $line->amount;
        $arg->line = $line;

        return $total;
    }
}
