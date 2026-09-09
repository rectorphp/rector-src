<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchWithInfiniteWhile
{
    public function run($a)
    {
        try {
            while (true) {
                if ($a) {
                    return 'A';
                }

                $a = doSomething();
            }
        } catch (Exception $e) {
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

class AlwaysTerminatedTryCatchWithInfiniteWhile
{
    public function run($a)
    {
        try {
            while (true) {
                if ($a) {
                    return 'A';
                }

                $a = doSomething();
            }
        } catch (Exception $e) {
            return null;
        }
    }
}

?>
