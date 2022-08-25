<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SkipEqualsFalse
{
    public function run(bool $value)
    {
        if ($value === false) {
            return 'not yet';
        }

        return 'yes';
    }

    public function runFromTheOtherSide(bool $value)
    {
        if (false === $value) {
            return 'not yet';
        }

        return 'yes';
    }
}
