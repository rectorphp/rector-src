<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class UnionOverSingleNative
{
    /**
     * @return bool|float|mixed|string
     */
    private function escapeQueryValue(string $value): string
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class UnionOverSingleNative
{
    private function escapeQueryValue(string $value): string
    {
    }
}

?>
