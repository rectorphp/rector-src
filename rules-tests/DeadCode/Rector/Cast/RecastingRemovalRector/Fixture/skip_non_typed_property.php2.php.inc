<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalNonStrictTypedProperty;

final class SkipNonTypedProperty2
{
    public function run(ExternalNonStrictTypedProperty $externalNonStrictTypedProperty)
    {
        $value = (int) $externalNonStrictTypedProperty->property;
    }
}
