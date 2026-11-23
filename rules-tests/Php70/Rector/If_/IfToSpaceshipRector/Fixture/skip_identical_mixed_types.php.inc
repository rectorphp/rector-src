<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class SkipIdenticalMixedTypes
{
    public function run($first, $second)
    {
        // "1" and 1 are not identical, but <=> sees them equal
        if ($first === $second) {
            return 0;
        }

        return ($first < $second) ? -1 : 1;
    }
}
