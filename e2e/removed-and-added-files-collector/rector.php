<?php

declare(strict_types=1);

use Maintenance\TestRector;

require_once __DIR__ . '/TestRector.php';

return static function (Rector\Config\RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TestRector::class);
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // TODO: Make it run in parallel.
    $rectorConfig->disableParallel();
};
