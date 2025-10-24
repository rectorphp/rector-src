<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class SideEffectChecks
{
    public function run()
    {
    }
}

?>
