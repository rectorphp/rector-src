<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // 4 valid files first, then 50 syntax errors, so the system error limit is reached mid-bucket
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
    ]);
};
