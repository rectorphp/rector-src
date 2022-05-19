<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

final class Foo
{
    protected const BAR = 1;
    public const BAZ = 1;
    public function run(): void
    {
        echo static::BAR;
        echo static::BAZ;
    }
}
?>
