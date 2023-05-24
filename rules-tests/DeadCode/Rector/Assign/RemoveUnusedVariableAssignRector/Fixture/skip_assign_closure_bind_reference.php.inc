<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class SkipAssignClosureBindReference
{
    public function run(object $container)
    {
        $containerLocked = &Closure::bind(static fn &($container) => $container->locked, null, $container)($container);
        $containerLocked = false;
    }
}
