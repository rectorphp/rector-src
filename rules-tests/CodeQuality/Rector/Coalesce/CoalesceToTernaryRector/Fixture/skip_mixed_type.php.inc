<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipMixedType
{
    public function run(mixed $name)
    {
        return $name ?? 'tom';
    }
}
