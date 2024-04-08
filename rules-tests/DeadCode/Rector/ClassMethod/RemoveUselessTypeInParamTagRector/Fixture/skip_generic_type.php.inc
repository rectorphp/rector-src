<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

final class SkipGenericType
{
    /**
     * @param iterable<stdClass> $foo
     */
    public function run(iterable $foo)
    {
    }
}
?>
