<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class StaticProperty
{
    public static $publicProperty;
    protected static $protectedProperty;

    public function foo()
    {
        return $this::$privateProperty;
    }
}
