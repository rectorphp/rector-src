<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class SkipWithVarDoc
{
    public function run(int $value): int|string
    {
        if ($value === 50) {
            /** @var string $value */
            $value = execute();
        }

        return $value;
    }
}
