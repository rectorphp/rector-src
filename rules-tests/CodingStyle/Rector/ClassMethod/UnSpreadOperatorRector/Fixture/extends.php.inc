<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector\Fixture;

class A
{
    /** @noRector UnSpreadOperatorRector */
    public static function make(... $arguments): static
    {
        return new static();
    }
}

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector\Fixture;

final class B extends A
{
    public static function make(... $arguments): static
    {
        return new static();
    }
}
?>
