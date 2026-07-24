<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class SkipConditionallyAssigned
{
    private ?stdClass $value = null;

    public function __construct(?stdClass $value)
    {
        if ($value instanceof stdClass) {
            $this->value = $value;
        }
    }
}
