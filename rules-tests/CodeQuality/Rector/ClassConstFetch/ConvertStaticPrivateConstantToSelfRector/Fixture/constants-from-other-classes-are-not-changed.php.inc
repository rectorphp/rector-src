<?php

namespace Utils\Rector\Tests\Rector\UseDateTimeImmutableRector\Fixture;

final class Foo
{
    public function run(): void
    {
        echo \DateTimeInterface::ATOM;
    }
}
?>
