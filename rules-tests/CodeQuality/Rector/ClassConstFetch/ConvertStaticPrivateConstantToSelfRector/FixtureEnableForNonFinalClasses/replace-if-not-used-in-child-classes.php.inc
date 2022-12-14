<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class ParentClass1
{
    private const BAR1 = 1;
    public function baz(): void
    {
        echo static::BAR1;
    }
}

class ChildClass1 extends ParentClass1 {}

?>
-----
<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

class ParentClass1
{
    private const BAR1 = 1;
    public function baz(): void
    {
        echo self::BAR1;
    }
}

class ChildClass1 extends ParentClass1 {}

?>
