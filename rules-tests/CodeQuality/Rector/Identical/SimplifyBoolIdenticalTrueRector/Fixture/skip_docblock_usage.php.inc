<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SkipDocblockUsage
{
    /** @var bool */
    private $prop;

    public function run()
    {
        return true === $this->prop ? 2 : 0;
    }
}
