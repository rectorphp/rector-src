<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class ParentClass2
{
    private const BAR2 = 1;
    public function baz(): void
    {
        echo static::BAR2;
    }
}

class ChildClass2 extends ParentClass2 {
    protected const BAR2 = 2;
}

?>
