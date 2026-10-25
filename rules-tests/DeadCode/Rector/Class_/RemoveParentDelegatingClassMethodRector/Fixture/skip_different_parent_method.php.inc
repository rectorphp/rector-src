<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipDifferentParentMethod extends SomeParentCommand
{
    protected function configure(): void
    {
        parent::anotherMethod();
    }
}
