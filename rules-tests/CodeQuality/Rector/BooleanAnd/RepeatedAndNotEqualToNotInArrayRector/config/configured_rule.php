<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanAnd\RepeatedAndNotEqualToNotInArrayRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RepeatedAndNotEqualToNotInArrayRector::class]);
