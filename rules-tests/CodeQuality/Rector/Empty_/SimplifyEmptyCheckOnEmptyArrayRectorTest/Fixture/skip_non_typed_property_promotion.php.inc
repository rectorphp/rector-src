<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipNonTypedPropertyPromotion
{
    /**
     * @param array $property
     */
    public function __construct(private $property)
    {
    }

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}
