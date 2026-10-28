<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // a single worker with more single-file jobs than its chunk budget (MAX_CHUNKS_PER_WORKER + 1) has to be respawned mid-run
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
    ]);
};
