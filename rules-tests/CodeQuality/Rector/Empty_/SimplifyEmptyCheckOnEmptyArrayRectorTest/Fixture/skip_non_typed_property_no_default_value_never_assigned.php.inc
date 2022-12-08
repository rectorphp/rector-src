<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNonTypedPropertyNoDefaultValueNeverAssigned
{
    /** @var array */
    public $property;

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}