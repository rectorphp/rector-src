<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipParamDocblock extends SomeParentCommand
{
    /**
     * @param positive-int $value
     */
    public function process(int $value): int
    {
        return parent::process($value);
    }
}
