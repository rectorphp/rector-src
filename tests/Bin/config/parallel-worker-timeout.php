<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Bin\Fixture\TimeoutRector;

require_once __DIR__ . '/../Fixture/TimeoutRector.php';

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/../Fixture/ParallelTimeout']);
    $rectorConfig->parallel(processTimeout: 1, maxNumberOfProcess: 1, jobSize: 1);
    $rectorConfig->rules([TimeoutRector::class]);
};
