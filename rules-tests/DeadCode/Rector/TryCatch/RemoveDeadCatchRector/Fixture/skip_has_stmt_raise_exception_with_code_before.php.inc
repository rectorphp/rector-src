<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

use InvalidArgumentException;

class SkipHasStmtRaiseExceptionWithCodeBefore
{
    public function run()
    {
        try {
            // some code
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $throwable) {
            $this->resetState();
            throw $throwable;
        }
    }

    private function resetState()
    {
    }
}
