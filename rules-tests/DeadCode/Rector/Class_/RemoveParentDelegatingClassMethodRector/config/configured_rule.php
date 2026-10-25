<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveParentDelegatingClassMethodRector::class]);
