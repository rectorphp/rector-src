<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class SkipWithDifferentThrowVariable
{
    public function run($variable)
    {
        try {
            // some code
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $throwable) {
            throw $variable;
        }
    }
}
