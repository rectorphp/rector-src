<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // 4 valid files first, so the 50th syntax error arrives with the 54th chunk, when a worker's chunk budget
    // (MAX_CHUNKS_PER_WORKER + 1) runs out while one job is left
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
    ]);
};
