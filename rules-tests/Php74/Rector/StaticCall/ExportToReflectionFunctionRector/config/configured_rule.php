<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\StaticCall\ExportToReflectionFunctionRector;

return RectorConfig::configure()
    ->withRules([ExportToReflectionFunctionRector::class]);
