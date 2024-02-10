<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector;

return RectorConfig::configure()
    ->withRules([ExplicitPublicClassMethodRector::class]);
