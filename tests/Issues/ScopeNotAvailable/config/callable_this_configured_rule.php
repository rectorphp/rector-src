<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules(
    [CallableThisArrayToAnonymousFunctionRector::class, CountArrayToEmptyArrayComparisonRector::class]
);
