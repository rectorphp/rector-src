<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class SkipDeadTryCatch
{
    public function run()
    {
        try {
            // some code
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }
}
