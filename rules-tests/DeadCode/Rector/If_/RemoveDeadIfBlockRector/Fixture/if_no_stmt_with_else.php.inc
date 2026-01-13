<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class IfNoStmtWithElse
{
    public function run($condition)
    {
        if ($condition) {
        } else {
            echo "something";
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class IfNoStmtWithElse
{
    public function run($condition)
    {
        if (!$condition) {
            echo "something";
        }
    }
}

?>
