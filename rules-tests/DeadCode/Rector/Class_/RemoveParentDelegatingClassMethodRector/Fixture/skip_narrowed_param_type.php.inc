<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipNarrowedParamType extends SomeParentCommand
{
    public function process(int|float $value): int
    {
        return parent::process($value);
    }
}
