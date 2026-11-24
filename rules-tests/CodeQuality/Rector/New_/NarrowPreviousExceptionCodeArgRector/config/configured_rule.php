<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\New_\NarrowPreviousExceptionCodeArgRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NarrowPreviousExceptionCodeArgRector::class]);
