<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class DemoFile
{
    /**
     * @param string $primitiveValue
     *   A primitive is fine.
     * @param callable $callableValue
     *   A PHP core class is fine.
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
