<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class SkipAnotherVariable
{
    public function run(?string $value, mixed $anotherValue): void
    {
        if (null === $value || ! is_string($anotherValue)) {
            return;
        }

        echo $value;
    }
}
