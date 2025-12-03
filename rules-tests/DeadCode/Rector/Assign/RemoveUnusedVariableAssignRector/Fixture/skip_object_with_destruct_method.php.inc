<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Source\SomeLock;

final class SkipObjectWithDestructMethod
{
    public function run()
    {
        $lock = $this->createLock();

        echo 'foobar';
    }

    private function createLock(): SomeLock
    {
        return new SomeLock();
    }
}