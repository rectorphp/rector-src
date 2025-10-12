<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector\Fixture;

final class AllowAnyType
{
    public function run($result)
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

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector\Fixture;

final class AllowAnyType
{
    public function run($result)
    {
        return $result;
    }
}

?>
