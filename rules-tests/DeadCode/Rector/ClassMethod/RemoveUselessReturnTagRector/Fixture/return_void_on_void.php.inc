<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class ReturnVoidOnVoid
{
    /**
     * @return void
     */
    public function resolve(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class ReturnVoidOnVoid
{
    public function resolve(): void
    {
    }
}

?>
