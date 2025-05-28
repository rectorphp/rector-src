<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class EmptyTryAndCatch
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
