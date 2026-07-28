<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

class SkipProtectedPropertyOnNonFinalClass
{
    protected int $value = 5;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
