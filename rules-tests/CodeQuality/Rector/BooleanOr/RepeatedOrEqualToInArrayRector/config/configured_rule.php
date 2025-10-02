<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RepeatedOrEqualToInArrayRector::class]);
