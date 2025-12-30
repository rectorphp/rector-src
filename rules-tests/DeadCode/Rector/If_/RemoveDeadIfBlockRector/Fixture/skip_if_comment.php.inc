<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class MergeComments
{
    public function run($b)
    {
        // toplevel
        if ($b) {
        } elseif (!$b) {
            // first inner
            f();
            // second inner
        }
    }
}

?>
