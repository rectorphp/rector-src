<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipNullableLeft
{
    public function run(?string $name)
    {
        return $name ?? 'tom';
    }
}
