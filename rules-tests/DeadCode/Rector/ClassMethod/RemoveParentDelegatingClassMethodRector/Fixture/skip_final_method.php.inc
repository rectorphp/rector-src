<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

class SkipFinalMethod extends SomeParentCommand
{
    final protected function configure(): void
    {
        parent::configure();
    }
}
