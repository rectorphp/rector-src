<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipWiderVisibility extends SomeParentCommand
{
    public function configure(): void
    {
        parent::configure();
    }
}
