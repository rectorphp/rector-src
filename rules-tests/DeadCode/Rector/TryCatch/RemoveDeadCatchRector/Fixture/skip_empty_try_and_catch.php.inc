<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class SkipEmptyTryAndCatch
{
    public function run()
    {
        try {
            // some code
        } catch (Throwable $throwable) {
        }
    }
}

?>
