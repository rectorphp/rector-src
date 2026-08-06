<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class NullableStringTruthyCheck
{
    public function run(?string $value): void
    {
        if ($value && is_string($value)) {
            echo $value;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class NullableStringTruthyCheck
{
    public function run(?string $value): void
    {
        if ($value) {
            echo $value;
        }
    }
}

?>
