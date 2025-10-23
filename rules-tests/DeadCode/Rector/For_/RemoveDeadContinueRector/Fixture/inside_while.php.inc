<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class InsideWhile
{
    public function run()
    {
       while (++$i < 10) {
           echo $i;
           continue;
       }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadContinueRector\Fixture;

final class InsideWhile
{
    public function run()
    {
       while (++$i < 10) {
           echo $i;
       }
    }
}

?>
