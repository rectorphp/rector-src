<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class NoStmtsInBothTryCatch
{
    public function run()
    {
        try {
        }
        catch (Throwable $throwable) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class NoStmtsInBothTryCatch
{
    public function run()
    {
    }
}

?>
