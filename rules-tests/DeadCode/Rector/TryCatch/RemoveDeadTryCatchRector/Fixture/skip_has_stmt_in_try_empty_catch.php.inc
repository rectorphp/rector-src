<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

use InvalidArgumentException;

class SkipHasStmtInTryEmptyCatch
{
    public function run()
    {
        try {
            $this->call();
        }
        catch (Throwable $throwable) {
        }
    }
}
