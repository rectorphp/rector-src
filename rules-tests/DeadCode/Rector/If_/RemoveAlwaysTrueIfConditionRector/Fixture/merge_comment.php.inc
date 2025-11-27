<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class MergeComments
{
    public function run()
    {
        // toplevel
        if (true) {
            // first inner
            f();
            // second inner
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class MergeComments
{
    public function run()
    {
        // toplevel
        // first inner
        f();
        // second inner
    }
}

?>
