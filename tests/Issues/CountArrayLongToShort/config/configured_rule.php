<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return RectorConfig::configure()
    ->withRules([CountArrayToEmptyArrayComparisonRector::class, LongArrayToShortArrayRector::class]);
