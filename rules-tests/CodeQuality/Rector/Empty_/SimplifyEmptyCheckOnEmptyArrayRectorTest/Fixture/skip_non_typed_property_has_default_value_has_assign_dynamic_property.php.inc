<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNonTypedPropertyHasDefaultValueHasAssignDynamicProperty
{
    /** @var array */
    public $property = [];

    public function assignDynamicProperty(string $dynamicProperty)
    {
        $this->$dynamicProperty = 'value';
    }

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}
