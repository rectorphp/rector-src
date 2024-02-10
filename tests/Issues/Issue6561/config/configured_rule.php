<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ExplicitBoolCompareRector::class, CountArrayToEmptyArrayComparisonRector::class]);
