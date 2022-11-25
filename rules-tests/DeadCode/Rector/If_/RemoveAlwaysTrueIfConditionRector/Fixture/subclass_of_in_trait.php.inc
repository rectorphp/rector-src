<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class Class1
{
    use TheTrait;
}

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class Class2
{
    use TheTrait;
}

trait TheTrait
{
    public static function theFunction(): void
    {
        if (!\is_subclass_of(static::class, Class1::class)) {
            echo "ok";

            return;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

// what is expected code?
// should remain the same? delete part below ----- (included)

?>
