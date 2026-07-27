<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipAssignReadsDefault
{
    private int $count = 0;

    public function __construct(int $extra)
    {
        $this->count = $this->count + $extra;
    }
}
