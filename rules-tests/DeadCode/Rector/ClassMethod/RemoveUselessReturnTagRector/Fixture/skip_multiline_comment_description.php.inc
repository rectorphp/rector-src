<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class SkipMultilineCommentDescription
{
    /**
     * @return UserlandClass
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

class UserlandClass
{
}

?>
