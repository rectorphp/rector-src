<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class DemoFile
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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class UserlandClass
{
}

?>
