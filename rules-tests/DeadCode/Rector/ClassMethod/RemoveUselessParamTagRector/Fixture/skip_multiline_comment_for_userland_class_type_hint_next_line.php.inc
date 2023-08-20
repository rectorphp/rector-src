<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use stdClass;

final class SkipMultilineCommentForUserlandClassTypeHint
{
    /**
     * @param string $primitiveValue
     *   A primitive is fine.
     * @param stdClass $userlandClass
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, stdClass $userlandClass)
    {
    }
}

