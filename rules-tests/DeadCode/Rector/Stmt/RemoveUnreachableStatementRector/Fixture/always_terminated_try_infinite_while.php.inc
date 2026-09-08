<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryInfiniteWhile
{
    public function run()
    {
        try {
            while (true) {
                doThings();
            }
        } catch (\Throwable $e) {
            return null;
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryInfiniteWhile
{
    public function run()
    {
        try {
            while (true) {
                doThings();
            }
        } catch (\Throwable $e) {
            return null;
        }
    }
}

?>
