<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

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
