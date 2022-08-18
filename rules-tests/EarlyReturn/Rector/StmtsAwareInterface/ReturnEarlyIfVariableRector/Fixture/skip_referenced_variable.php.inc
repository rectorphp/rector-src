<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class SkipReferencedVariable
{
    public function run(&$value)
    {
        if ($value === 50) {
            $value = 100;
        }

        return $value;
    }
}
