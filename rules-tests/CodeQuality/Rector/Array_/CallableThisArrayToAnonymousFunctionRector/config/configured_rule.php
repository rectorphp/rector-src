<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CallableThisArrayToAnonymousFunctionRector::class]);
