<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class SomeClass
{
    public function run(array $result)
    {
        if ($result === []) {
            return [];
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class SomeClass
{
    public function run(array $result)
    {
        return $result;
    }
}

?>
