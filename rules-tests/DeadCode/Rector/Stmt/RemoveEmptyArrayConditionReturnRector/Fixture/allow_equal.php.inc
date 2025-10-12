<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector\Fixture;

final class AllowEqual
{
    public function run(array $result)
    {
        if ($result == []) {
            return [];
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector\Fixture;

final class AllowEqual
{
    public function run(array $result)
    {
        return $result;
    }
}

?>
