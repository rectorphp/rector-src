<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class Foo
{
    private const BAR = 1;
    public function baz(): void
    {
        echo static::BAR;
    }
}
?>
