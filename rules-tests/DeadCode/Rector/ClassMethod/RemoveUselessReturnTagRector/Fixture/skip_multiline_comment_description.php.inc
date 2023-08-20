<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Source\SomeConstantType;

class SkipMultilineCommentDescription
{
    /**
     * @return SomeConstantType
     *   A userland class is fine.
     */
    public function userland(): callable
    {
    }

    /**
     * @return callable
     *   A PHP core class is not.
     */
    public function phpCore(): callable
    {
    }

    /**
     * @return string
     *   Neither is a primitive.
     */
    public function primitive(): string
    {
    }
}

?>
