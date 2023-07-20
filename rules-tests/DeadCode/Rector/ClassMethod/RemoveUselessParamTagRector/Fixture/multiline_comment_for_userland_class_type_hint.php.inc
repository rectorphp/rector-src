<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class MultilineCommentForUserlandClassTypeHint
{
    /**
     * @param string $primitiveValue
     *   A primitive is fine.
     * @param UserlandClass $userlandClass
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, UserlandClass $userlandClass)
    {
    }
}

class UserlandClass
{
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class MultilineCommentForUserlandClassTypeHint
{
    /**  A primitive is fine.
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, UserlandClass $userlandClass)
    {
    }
}

class UserlandClass
{
}

?>
