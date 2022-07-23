<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        SimplifyIfNotNullReturnRector::class,
        ExplicitBoolCompareRector::class,
        NewlineAfterStatementRector::class,
    ]);
};
