<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

final class WithInterpolatedStringPart
{
    public function test(array $checkInputs): void
    {
        foreach ($checkInputs as $index => $checkInput) {
            echo "Input#{$index}: Wrong tokenTime";
        }
    }
}
