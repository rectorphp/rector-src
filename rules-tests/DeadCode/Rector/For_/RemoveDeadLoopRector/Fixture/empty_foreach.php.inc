<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyForeach
{
    public function run($values)
    {
        foreach ($values as $value) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyForeach
{
    public function run($values)
    {
    }
}

?>
