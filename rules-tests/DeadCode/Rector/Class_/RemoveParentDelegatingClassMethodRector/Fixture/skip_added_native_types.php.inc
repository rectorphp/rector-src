<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithDocblockTypes;

final class SkipAddedNativeTypes extends SomeParentWithDocblockTypes
{
    public function compute(int $value): int
    {
        return parent::compute($value);
    }
}
