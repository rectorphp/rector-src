<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class ContinueInsideForeach
{
    public function run($values)
    {
        foreach ($values as $value) {
            var_dump($value);
            continue;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class ContinueInsideForeach
{
    public function run($values)
    {
        foreach ($values as $value) {
            var_dump($value);
        }
    }
}

?>
