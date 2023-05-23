<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Source\HeavyClass;
use RuntimeException;

final class SkipTryCatchOnNewInstance
{
    public function run()
    {
        try {
            $obj = new HeavyClass();
        } catch (RuntimeException $r) {
            throw $r;
        }
    }
}
