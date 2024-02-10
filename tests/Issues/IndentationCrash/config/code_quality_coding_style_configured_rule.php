<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withRules(
        [
            SimplifyIfReturnBoolRector::class,
            NewlineAfterStatementRector::class,
            StringClassNameToClassConstantRector::class,
        ]
    );
