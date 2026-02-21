<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

function yAxisScale($results)
{
    $stats = (object) [
        'maxRange' => 0,
        'data'     => [],
    ];

    foreach ($results as $item) {
        if ($item->value > $stats->maxRange) {
            $stats->maxRange = $item->value;
        }
    }

    $stats->maxRange = (intval(($stats->maxRange + 1) / 500) + 1) * 500;

    return $stats->maxRange;
}
