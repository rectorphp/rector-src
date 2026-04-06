<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipUnionNullableLeft
{
    public function run(null|string $name)
    {
        return $name ?? 'tom';
    }
}
