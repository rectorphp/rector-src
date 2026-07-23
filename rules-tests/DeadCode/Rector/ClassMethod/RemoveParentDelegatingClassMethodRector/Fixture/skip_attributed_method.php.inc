<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipAttributedMethod extends SomeParentCommand
{
    #[\Deprecated]
    protected function configure(): void
    {
        parent::configure();
    }
}
