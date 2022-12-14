<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class ParentClass3
{
    private const BAR3 = 1;
    public function baz(): void
    {
        echo static::BAR3;
    }
}

class ChildClass3 extends ParentClass3 {
    public const BAR3 = 2;
}

?>
