<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

use Rector\Exception\ShouldNotHappenException;
use Throwable;

class SkipNextThrowableHasSpecialCondition
{
    public function run()
    {
        try {
            // some code
        } catch (ShouldNotHappenException $exception) { // on child, specifically ShouldNotHappenException, just throw
            throw $exception;
        } catch (Throwable $throwable) {
            // on parent any Throwable, with special condition message, just return
            if ($throwable->getMessage() === 'Special condition') {
                return;
            }

            throw $throwable;
        }
    }
}

?>
