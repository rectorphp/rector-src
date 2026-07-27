<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipEarlyReturnInConstructor
{
    public int $x = 0;

    public function __construct(bool $shouldReturn = false)
    {
        if ($shouldReturn) {
            return;
        }

        $this->x = 100;
    }
}
