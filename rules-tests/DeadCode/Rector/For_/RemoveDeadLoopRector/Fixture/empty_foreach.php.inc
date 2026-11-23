<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class EmptyForeach
{
    public function run(array $values)
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
    public function run(array $values)
    {
    }
}

?>
