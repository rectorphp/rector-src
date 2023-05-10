<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector\Fixture;

final class SkipSingleOne
{
    /**
     * @var int|null
     */
    private $first;

    public function run()
    {
        if (null !== $this->first) {
            return $this->first;
        }

        return null;
    }
}
