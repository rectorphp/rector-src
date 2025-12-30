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

        if ($this->someCall()) {
        }

        if (false) {
        } elseif ($this->anotherCall()) {
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
        if ($this->someCall()) {
        }

        if (!false && $this->anotherCall()) {
        }
    }
}

?>
