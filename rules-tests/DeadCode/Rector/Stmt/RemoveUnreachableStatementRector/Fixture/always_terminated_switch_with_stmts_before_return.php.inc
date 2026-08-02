<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitchWithStmtsBeforeReturn
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                echo 'a';
                return 'A';
            default:
                echo 'default';
                return 'B';
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitchWithStmtsBeforeReturn
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                echo 'a';
                return 'A';
            default:
                echo 'default';
                return 'B';
        }
    }
}

?>
