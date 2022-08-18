<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitch
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                return 'A';
            default:
                return 'B';
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitch
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                return 'A';
            default:
                return 'B';
        }
    }
}

?>
