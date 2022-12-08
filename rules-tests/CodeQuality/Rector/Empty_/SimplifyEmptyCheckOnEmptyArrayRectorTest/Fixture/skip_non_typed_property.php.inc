<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNonTypedProperty
{
    /** @var array */
    public $property;

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}