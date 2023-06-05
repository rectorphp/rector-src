<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector\Fixture;

class InClassMethod
{
    function run($value) {
        if ($value === 1000) {
            return;
        }

        if ($value) {
            return;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector\Fixture;

class InClassMethod
{
    function run($value) {
        if ($value === 1000) {
            return;
        }

        if ($value) {
        }
    }
}

?>
