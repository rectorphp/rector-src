<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class SkipDeadCatchChildOfLaterCatch
{
    public function run()
    {
        try {
            // some code
        } catch (\App\ChildException $exception) {
            throw $exception;
        } catch (\App\ParentException $exception) {
            echo 'parent';
        }
    }
}

?>
