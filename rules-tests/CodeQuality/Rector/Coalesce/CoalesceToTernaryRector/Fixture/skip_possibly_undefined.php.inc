<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipPossiblyUndefined
{
    public function run()
    {
        if (rand(0, 1)) {
            $name = 'tom';
        }

        return $name ?? 'tom';
    }
}
