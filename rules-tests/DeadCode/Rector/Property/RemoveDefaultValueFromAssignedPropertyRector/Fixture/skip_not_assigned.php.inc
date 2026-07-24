<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class SkipNotAssigned
{
    private ?stdClass $value = null;

    public function __construct()
    {
    }
}
