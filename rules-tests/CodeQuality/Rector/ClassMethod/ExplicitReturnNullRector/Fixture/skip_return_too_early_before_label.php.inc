<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipReturnTooEarlyBeforeLabel
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
