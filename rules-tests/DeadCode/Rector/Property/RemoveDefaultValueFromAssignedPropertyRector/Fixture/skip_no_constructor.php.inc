<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class SkipNoConstructor
{
    private ?stdClass $value = null;
}
