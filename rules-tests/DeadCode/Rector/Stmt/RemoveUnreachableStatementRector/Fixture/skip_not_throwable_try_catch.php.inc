<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class SkipNotThrowableTryCatch
{
    public function run()
    {
        try {
            return something();
        } catch (Exception $e) {
            return null;
        }

        echo 'reachable when an Error escapes the catch';
    }
}

?>
