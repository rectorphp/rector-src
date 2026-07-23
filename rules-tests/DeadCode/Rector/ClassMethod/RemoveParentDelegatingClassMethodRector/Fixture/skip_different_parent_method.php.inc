<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipDifferentParentMethod extends SomeParentCommand
{
    protected function configure(): void
    {
        parent::anotherMethod();
    }
}
