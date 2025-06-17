<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

function retry(int $retries, callable $fn)
{
    beginning:
    try {
        return $fn();
    } catch (\Exception $exception) {
        if ($retries === 0) {
            throw new \FailingTooHardException('', 0, $exception);
        }

        --$retries;

        goto beginning;
    }
}
