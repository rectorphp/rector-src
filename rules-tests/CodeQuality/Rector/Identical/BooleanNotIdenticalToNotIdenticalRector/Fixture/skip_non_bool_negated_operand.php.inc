<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector\Fixture;

class SkipNonBoolNegatedOperand
{
    public function run(int $a, bool $b)
    {
        return ! $a === $b;
    }
}
