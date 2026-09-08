<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchCommentInCatch
{
    public function run()
    {
        try {
            return something();
        } catch (\Throwable $e) {
            return null;
            // some comment
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchCommentInCatch
{
    public function run()
    {
        try {
            return something();
        } catch (\Throwable $e) {
            return null;
            // some comment
        }
    }
}

?>
