<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipNotDefined
{
    public function run()
    {
        return $name ?? 'tom';
    }
}
