<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel(maxNumberOfProcess: 2, jobSize: 1);

    $rectorConfig->paths([
        __DIR__ . '/src/',
    ]);

    $rectorConfig->rules([
        RemoveUnusedPrivatePropertyRector::class,
        RemoveUnusedPrivateMethodRector::class,
    ]);
};
