<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector;

return RectorConfig::configure()
    ->withRules([ArrowFunctionToAnonymousFunctionRector::class]);
