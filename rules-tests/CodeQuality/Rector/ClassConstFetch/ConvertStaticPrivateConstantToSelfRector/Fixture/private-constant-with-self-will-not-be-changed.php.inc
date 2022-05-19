<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

final class Foo
{
    private const BAR = 1;
    public function baz(): void
    {
        echo self::BAR;
    }
}
?>
