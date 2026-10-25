<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipDeprecatedDocblock extends SomeParentCommand
{
    /**
     * @deprecated
     */
    protected function configure(): void
    {
        parent::configure();
    }
}
