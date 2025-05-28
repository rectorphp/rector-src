<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class SkipHasStmtEmptyCatchThrow
{
    public function run()
    {
        try {
            // some code
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $throwable) {
        }
    }
}
