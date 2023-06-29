<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheClass(FileCacheStorage::class);

    if (getenv('RECTOR_DISABLE_PARALLEL') === 'true') {
        $rectorConfig->disableParallel();
    }

    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->rule(ChangeAndIfToEarlyReturnRector::class);
    $rectorConfig->rule(NewlineAfterStatementRector::class);
};
