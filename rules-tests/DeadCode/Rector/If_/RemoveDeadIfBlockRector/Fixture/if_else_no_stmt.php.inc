<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class IfElseNoStmt
{
    public function run($condition)
    {
        if ($condition) {
            echo "something";
        } else {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class IfElseNoStmt
{
    public function run($condition)
    {
        if ($condition) {
            echo "something";
        }
    }
}

?>
