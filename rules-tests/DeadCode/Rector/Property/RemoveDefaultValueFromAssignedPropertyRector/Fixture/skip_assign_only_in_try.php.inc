<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use Throwable;

final class SkipAssignOnlyInTry
{
    private ?string $name = null;

    public function __construct(string $name)
    {
        try {
            $this->name = json_encode($name, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
        }
    }
}
