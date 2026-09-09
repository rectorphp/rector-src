<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedIfElseWithTrailingComment
{
    public function run($a)
    {
        if ($a) {
            return 'A';
            // and that is all
        } else {
            return 'B';
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedIfElseWithTrailingComment
{
    public function run($a)
    {
        if ($a) {
            return 'A';
            // and that is all
        } else {
            return 'B';
        }
    }
}

?>
