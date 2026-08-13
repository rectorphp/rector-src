<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedInfiniteWhile
{
    public function run($a)
    {
        while (true) {
            if ($a) {
                return 'A';
            }

            $a = doSomething();
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedInfiniteWhile
{
    public function run($a)
    {
        while (true) {
            if ($a) {
                return 'A';
            }

            $a = doSomething();
        }
    }
}

?>
