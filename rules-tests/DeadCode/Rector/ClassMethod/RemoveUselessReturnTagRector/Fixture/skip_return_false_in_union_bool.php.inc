<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class SkipReturnFalseOnUnionBool
{
    /**
     * @return false|\stdClass
     */
    function run(): bool|\stdClass
    {
        if (rand(0, 1)) {
            return false;
        }

        return new \stdClass();
    }
}
