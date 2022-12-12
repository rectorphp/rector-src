<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class SkipMethodCallReturnDocblockString
{
    public function run(): string
    {
        return (string) $this->getResultWithDocblock();
    }

    /**
     * @return string
     */
    private function getResultWithDocblock()
    {
        return 1;
    }
}
