<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class SkipNotThrowableTryCatchWithStmtsBeforeReturn
{
    public function run()
    {
        try {
            echo 'try';
            return something();
        } catch (Exception $e) {
            echo 'catch';
            return null;
        }

        echo 'reachable when an Error escapes the catch';
    }
}

?>
