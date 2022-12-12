<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

/**
 * @return string
 */
function getResult()
{
    return 1;
}

class SkipMethodCallReturnDocblockString
{
    public function run(): string
    {
        return (string) getResult();
    }
}
