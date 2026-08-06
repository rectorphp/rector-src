<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class NullableIntFromMethodCall
{
    public function run(): void
    {
        $count = $this->resolveCount();
        if ($count === null || ! is_int($count)) {
            return;
        }

        echo $count;
    }

    private function resolveCount(): ?int
    {
        return 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class NullableIntFromMethodCall
{
    public function run(): void
    {
        $count = $this->resolveCount();
        if ($count === null) {
            return;
        }

        echo $count;
    }

    private function resolveCount(): ?int
    {
        return 1;
    }
}

?>
