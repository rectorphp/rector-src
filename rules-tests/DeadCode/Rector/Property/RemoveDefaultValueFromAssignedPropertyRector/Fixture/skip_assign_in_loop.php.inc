<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class SkipAssignInLoop
{
    public int $x = 0;

    /**
     * @param iterable<int> $items
     */
    public function __construct(iterable $items) {
        foreach ($items as $item) {
            if ($item > 5) {
                $this->x = 1;
            } else {
                $this->x = -1;
            }
        }
    }
}
