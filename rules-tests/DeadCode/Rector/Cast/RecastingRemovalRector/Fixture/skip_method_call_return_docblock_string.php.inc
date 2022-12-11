<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class SkipMethodCallReturnDocblockString
{
    public function run(): string
    {
        return (string) $this->getResult();
    }

    /**
     * @return string
     */
    private function getResult()
    {
        return 1;
    }
}