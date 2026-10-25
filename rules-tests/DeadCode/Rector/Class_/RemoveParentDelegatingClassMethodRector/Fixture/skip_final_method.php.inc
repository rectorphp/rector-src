<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

class SkipFinalMethod extends SomeParentCommand
{
    final protected function configure(): void
    {
        parent::configure();
    }
}
