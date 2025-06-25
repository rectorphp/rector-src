<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipExitTooEarlyBeforeLabel
{
    public function run()
    {
        exit;

        if (rand(0, 1)) {
            return 1;
        }

        unusedLabel:
            echo 'unused';
    }
}
