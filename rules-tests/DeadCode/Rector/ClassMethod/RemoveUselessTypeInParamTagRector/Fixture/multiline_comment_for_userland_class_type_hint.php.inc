<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

final class MultilineCommentForUserlandClassTypeHint
{
    /**
     * @param string $primitiveValue start on first line
     *   A primitive is fine.
     * @param callable $callableValue start on first line
     *   A PHP core class is fine.
     * @param AnotherUserlandClass $userlandClass start on first line
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, AnotherUserlandClass $userlandClass)
    {
    }
}

class AnotherUserlandClass
{
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

final class MultilineCommentForUserlandClassTypeHint
{
    /**
     * @param $primitiveValue start on first line
     *   A primitive is fine.
     * @param $callableValue start on first line
     *   A PHP core class is fine.
     * @param $userlandClass start on first line
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, AnotherUserlandClass $userlandClass)
    {
    }
}

class AnotherUserlandClass
{
}
?>
