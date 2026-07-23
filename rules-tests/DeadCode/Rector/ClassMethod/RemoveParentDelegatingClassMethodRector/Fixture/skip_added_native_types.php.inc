<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithDocblockTypes;

final class SkipAddedNativeTypes extends SomeParentWithDocblockTypes
{
    public function compute(int $value): int
    {
        return parent::compute($value);
    }
}
