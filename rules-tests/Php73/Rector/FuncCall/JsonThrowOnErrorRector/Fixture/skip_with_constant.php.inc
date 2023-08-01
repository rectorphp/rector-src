<?php

namespace Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\Fixture;

class SkipWithConstant
{
    private const MIN_DELAY = 1000;

    private const MAX_DELAY = self::MIN_DELAY * 60 * 10;

    public function getWaitingTime(): int
    {
        return min(self::MAX_DELAY, 0);
    }
}
