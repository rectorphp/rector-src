<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class AnyExprCompare
{
    public function run(array $result)
    {
        if ($result == 999) {
            return 999;
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class AnyExprCompare
{
    public function run(array $result)
    {
        return $result;
    }
}

?>
