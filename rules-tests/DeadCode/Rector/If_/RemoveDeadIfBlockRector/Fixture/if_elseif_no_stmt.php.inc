<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class IfElseNoStmt
{
    public function run($condition)
    {
        if ($condition) {
            echo "something";
        } elseif (!$condition) {
        } elseif (true) {
            echo "global";
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
        } elseif (true) {
            echo "global";
        }
    }
}

?>
