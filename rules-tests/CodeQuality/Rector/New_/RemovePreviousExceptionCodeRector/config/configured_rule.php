<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\New_\RemovePreviousExceptionCodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RemovePreviousExceptionCodeRector::class]);
