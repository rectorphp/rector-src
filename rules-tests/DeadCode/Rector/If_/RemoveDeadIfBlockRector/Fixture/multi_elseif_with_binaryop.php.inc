<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class MultiElseIfWithBinaryOp
{
    public function run($value, $flag)
    {
        if ($flag) {
        } elseif ($value === 2) {
        } elseif ($value === 3 || $value === 4) {
            echo "matched";
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class MultiElseIfWithBinaryOp
{
    public function run($value, $flag)
    {
        if (!$flag && ($value === 3 || $value === 4)) {
            echo "matched";
        }
    }
}

?>
