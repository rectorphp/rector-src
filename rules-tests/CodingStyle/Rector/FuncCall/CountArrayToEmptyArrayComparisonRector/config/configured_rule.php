<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CountArrayToEmptyArrayComparisonRector::class]);
