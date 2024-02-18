<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyEmptyArrayCheckRector::class]);
