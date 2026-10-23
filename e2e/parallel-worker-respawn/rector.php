<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // a single worker streams all its single-file jobs as separate chunks and must report every one
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
    ]);
};
