<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

use Exception;

class AlwaysTerminatedTryCatchWithStmtsBeforeReturn
{
    public function run()
    {
        try {
            echo 'try';
            return something();
        } catch (Exception $e) {
            echo 'catch';
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

class AlwaysTerminatedTryCatchWithStmtsBeforeReturn
{
    public function run()
    {
        try {
            echo 'try';
            return something();
        } catch (Exception $e) {
            echo 'catch';
            return null;
        }
    }
}

?>
