<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class SideEffectChecks
{
    public function run()
    {
        if (10) {
        }

        if (true) {
        }

        if (5 + 10) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlocksRector\Fixture;

class SideEffectChecks
{
    public function run()
    {
    }
}

?>
