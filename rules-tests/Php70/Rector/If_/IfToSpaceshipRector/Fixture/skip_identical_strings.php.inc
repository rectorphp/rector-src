<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class SkipIdenticalStrings
{
    public function run(string $first, string $second)
    {
        // "1" and "01" are not identical, but <=> sees them equal
        if ($first === $second) {
            return 0;
        } else {
            return ($first < $second) ? -1 : 1;
        }
    }
}
