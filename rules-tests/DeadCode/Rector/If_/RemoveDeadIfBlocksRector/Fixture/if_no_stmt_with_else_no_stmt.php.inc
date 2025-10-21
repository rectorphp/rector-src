<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class IfNoStmtWithElseNoStmt
{
    public function run($condition)
    {
        if ($condition) {
        } else {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class IfNoStmtWithElseNoStmt
{
    public function run($condition)
    {
    }
}

?>
