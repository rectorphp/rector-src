<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector\Fixture;

class SkipIndirectReturn
{
    protected ?\stdClass $prop1 = null;
    protected ?\stdClass $prop2 = null;

    public function getProp()
    {
        if ($this->prop1 !== null) {
            return $this->prop1;
        }
        if ($this->prop2 !== null) {
            return $this->prop2;
        }
        echo 'hi';
        return null;
    }
}
