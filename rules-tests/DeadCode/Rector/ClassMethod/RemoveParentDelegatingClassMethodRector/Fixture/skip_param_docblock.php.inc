<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

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
