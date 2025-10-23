<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyDo
{
    public function run()
    {
        do {

        } while (++$i < 10);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyDo
{
    public function run()
    {
    }
}

?>
