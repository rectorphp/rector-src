<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class OnlyCommentInFinally
{
    public function run()
    {
        try {
            // some code
        } catch (\Throwable $throwable) {
            throw $throwable;
        } finally {
            // a comment
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class OnlyCommentInFinally
{
    public function run()
    {
    }
}

?>
