<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedIfElseWithStmtsBeforeReturn
{
    public function run($a)
    {
        if ($a) {
            echo 'if';
            return 'A';
        } else {
            echo 'else';
            return 'B';
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedIfElseWithStmtsBeforeReturn
{
    public function run($a)
    {
        if ($a) {
            echo 'if';
            return 'A';
        } else {
            echo 'else';
            return 'B';
        }
    }
}

?>
