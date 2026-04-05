<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

class SkipDocblockBased
{
    /**
     * @param string $name
     */
    public function run($name)
    {
        return $name ?? 'tom';
    }
}
