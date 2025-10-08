<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\FixturePhp74;

final class SkipSubstr
{
    public function run(string $value): string
    {
        return (string) substr($value, 10, 10);
    }
}
