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

    // the only worker ends on File3.php, so results for File3.php and File4.php are missing;
    // the syntax error in File2.php must not hide that
    $rectorConfig->parallel(maxNumberOfProcess: 1, jobSize: 1);

    $rectorConfig->rules([
        LongArrayToShortArrayRector::class,
        ExitWorkerRector::class,
    ]);
};
