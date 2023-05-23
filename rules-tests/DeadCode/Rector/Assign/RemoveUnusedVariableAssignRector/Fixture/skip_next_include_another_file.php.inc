<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class SkipNextIncludeAnotherFile
{
    public function run()
    {
        $variable = 'test';

        if (rand(0, 1)) {
            include 'test.php';
        }
    }
}
