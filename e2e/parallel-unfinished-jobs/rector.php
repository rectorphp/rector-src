<?php

declare(strict_types=1);

use E2e\Parallel\UnfinishedJobs\ExitWorkerRector;
use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

require_once __DIR__ . '/utils/ExitWorkerRector.php';

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // size-based scheduling processes File3.php last, where the only worker exits without reporting it;
    // the syntax error in File2.php must not hide that
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
        ExitWorkerRector::class,
    ]);
};
