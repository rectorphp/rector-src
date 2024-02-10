<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveEmptyClassMethodRector::class]);
