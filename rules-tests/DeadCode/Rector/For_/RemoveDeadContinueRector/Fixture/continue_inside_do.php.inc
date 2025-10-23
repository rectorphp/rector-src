<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class ContinueInsideDo
{
    public function run()
    {
        do {
            echo ".";
            continue;
        } while (++$i < 10);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class ContinueInsideDo
{
    public function run()
    {
        do {
            echo ".";
        } while (++$i < 10);
    }
}

?>
