<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchWithTrailingComment
{
    public function run()
    {
        try {
            return something();
            // nothing to do here
        } catch (Exception $e) {
            return null;
        }

        echo 'never executed';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchWithTrailingComment
{
    public function run()
    {
        try {
            return something();
            // nothing to do here
        } catch (Exception $e) {
            return null;
        }
    }
}

?>
