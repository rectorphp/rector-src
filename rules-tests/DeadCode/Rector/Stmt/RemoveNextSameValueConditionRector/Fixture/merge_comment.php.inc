<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector\Fixture;

final class MergeComment
{
    public function __construct(array $items)
    {
        $count = 100;

        // 1
        if ($items === []) {
            $count = 0;
        }

        // 2
        if ($items === []) {
            return $count;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector\Fixture;

final class MergeComment
{
    public function __construct(array $items)
    {
        $count = 100;

        // 1
        // 2
        if ($items === []) {
            $count = 0;
            return $count;
        }
    }
}

?>
