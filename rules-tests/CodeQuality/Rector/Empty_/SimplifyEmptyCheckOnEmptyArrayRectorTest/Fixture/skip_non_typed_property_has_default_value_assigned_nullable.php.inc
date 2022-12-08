<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNonTypedPropertyHasDefaultValueAssignedNullable
{
    /** @var array */
    public $property = [];

    public function run()
    {
        if (rand(0, 1)) {
            $this->property = null;
        } else {
            $this->property = [];
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}
