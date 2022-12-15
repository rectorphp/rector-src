<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

/**
 * @see https://phpstan.org/r/6fbad07c-4b5e-4d89-8970-03df898abc6e
 */
final class SkipReturnStandaloneTrueFalseByReturnBool
{
    /**
     * @return true
     */
    function someTrue(): bool
    {
        return true;
    }

    /**
     * @return false
     */
    function someFalse(): bool
    {
        return false;
    }
}
