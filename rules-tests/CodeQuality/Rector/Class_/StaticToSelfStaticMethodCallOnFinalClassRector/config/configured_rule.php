<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([StaticToSelfStaticMethodCallOnFinalClassRector::class]);
