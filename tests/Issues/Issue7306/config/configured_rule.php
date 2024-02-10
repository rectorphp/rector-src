<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules(
        [SimplifyIfNotNullReturnRector::class, ExplicitBoolCompareRector::class, NewlineAfterStatementRector::class]
    );
