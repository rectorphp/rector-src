<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipNullType
{
    public function run(null $name)
    {
        return $name ?? 'tom';
    }
}
