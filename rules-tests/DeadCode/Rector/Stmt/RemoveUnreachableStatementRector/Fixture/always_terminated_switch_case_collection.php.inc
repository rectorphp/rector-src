<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitchCaseCollection
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
            case 'b':
                return 'AB';
            default:
                return 'C';
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class AlwaysTerminatedSwitchCaseCollection
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
            case 'b':
                return 'AB';
            default:
                return 'C';
        }
    }
}

?>
