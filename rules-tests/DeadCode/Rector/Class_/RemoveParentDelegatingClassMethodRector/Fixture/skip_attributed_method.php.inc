<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipAttributedMethod extends SomeParentCommand
{
    #[\Deprecated]
    protected function configure(): void
    {
        parent::configure();
    }
}
