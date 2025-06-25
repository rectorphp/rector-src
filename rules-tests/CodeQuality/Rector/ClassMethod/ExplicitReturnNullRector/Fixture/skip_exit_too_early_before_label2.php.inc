<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipExitTooEarlyBeforeLabel2
{
    public function run()
    {
        if (rand(0, 1)) {
            return 1;
        } else {
            return null;
        }

        unusedLabel:
            echo 'unused';
    }
}
