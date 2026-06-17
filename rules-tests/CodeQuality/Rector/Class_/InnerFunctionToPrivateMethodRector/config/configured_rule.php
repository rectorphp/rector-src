<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([InnerFunctionToPrivateMethodRector::class]);
