<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

final class SkipNonTypedProperty
{
    /** @var int */
    public $property = 1;

    public function run()
    {
        $value = (int) $this->property;
    }
}
