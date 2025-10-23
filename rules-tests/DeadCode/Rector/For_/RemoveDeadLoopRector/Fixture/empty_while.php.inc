<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyWhile
{
    public function run()
    {
       while (++$i < 10) {
       }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyWhile
{
    public function run()
    {
    }
}

?>
