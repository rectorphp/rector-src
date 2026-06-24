<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NegatedAndsToPositiveOrsRector::class]);
