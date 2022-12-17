<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class Foo
{
    private const BAR = 1;
    private function baz(): void
    {
        echo static::BAR;
    }
}
?>
-----
<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class Foo
{
    private const BAR = 1;
    private function baz(): void
    {
        echo self::BAR;
    }
}
?>
